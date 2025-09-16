<?php

namespace App\Http\Controllers\FlightTicket;

use Carbon\Carbon;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Services\CreditService;
use App\Exports\BookTicketExport;
use App\Services\SupplierService;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Owner;
use Illuminate\Support\Facades\DB;
use App\Enums\BookTicketStatusEnum;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Airport;
use App\Models\FlightTicket\Credits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Models\FlightTicket\BookTicket;
use App\Models\FlightTicket\Destination;
use App\Mail\SupplierBookingConfirmation;
use App\Enums\BookTicketBookingSourceEnum;
use App\Models\FlightTicket\SerialCounter;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\PurchaseTicketFareLog;
use App\Models\FlightTicket\Accounts\SupplierTransaction;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:book_ticket show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {

        $flight_no = PurchaseEntry::select('flight_no')->distinct()->get();
        $airports  = Airport::where('status', 1)->get();
        $airlines  = Airline::where('status', 1)->pluck('name', 'id')->all();
        $destinations = Destination::where('status', 1)->get();
        $owners       = Owner::pluck('name', 'id')->all();
        $suppliers    = Owner::where('is_third_party', '=', 1)->get();

        $q = \App\PurchaseEntry::join('destinations as d', 'd.id', '=', 'purchase_entries.destination_id')
            ->join('airlines as a', 'a.id', '=', 'purchase_entries.airline_id')
            ->join('owners as o', 'o.id', '=', 'purchase_entries.owner_id')

            ->orderBy('travel_date', 'ASC')
            ->whereNotNull('purchase_entries.created_at');

        if ($request->has('search')) {

            if ($request->has('owner_id') && $request->owner_id != '')
                $q->where('purchase_entries.owner_id', $request->owner_id);

            if ($request->has('supplier_id') && $request->supplier_id != '')
                $q->where('purchase_entries.owner_id', $request->supplier_id);


            if ($request->has('flight_no') && $request->flight_no != '')
                $q->where('purchase_entries.flight_no', $request->flight_no);

            if ($request->has('namelist_status_id') && $request->namelist_status_id != '') {
                $name_list_id = $request->namelist_status_id;
                if ($name_list_id == 6) {
                    $name_list_id = 0;
                }
                $q->where('purchase_entries.namelist_status', $name_list_id);
            }

            if ($request->has('airport_id') && $request->airport_id != '') {
                $destination_id = Destination::where('origin_id', $request->airport_id)->where('status', 1)->pluck('id')->all();
                $q->whereIn('purchase_entries.destination_id', $destination_id);
            } elseif ($request->has('destination_id') && $request->destination_id != '') {
                $q->where('purchase_entries.destination_id', $request->destination_id);
            }

            if ($request->has('pnr_no') && $request->pnr_no != '')
                $q->where('pnr', 'like', '%' . $request->pnr_no . '%');

            if ($request->has('travel_date_to') && $request->travel_date_to != '') {
                $from = Carbon::parse($request->travel_date_from);
                $to  = Carbon::parse($request->travel_date_to);
                $q->whereBetween('purchase_entries.travel_date', [$from, $to]);

                if (
                    $from->format('Y-m-d') <= '2023-03-31' ||
                    $to->format('Y-m-d') <= '2023-03-31'
                ) {
                    $data = collect([]);
                    return view('flight-tickets.bookings.index', compact('airports', 'flight_no', 'data', 'destinations', 'airlines', 'owners', 'suppliers'));
                }
            } else {
                $q->whereDate('purchase_entries.travel_date', '>=', '2023-04-01');
                $request->session()->forget(['previous_day_date', 'next_day_date']);
            }

            if ($request->has('namelist_date') && $request->namelist_date != '') {
                $namelist_date = Carbon::parse($request->namelist_date);
                $q->whereDate('purchase_entries.name_list', $namelist_date);
            }

            if ($request->has('destination_order') && $request->destination_order == 'asc' || $request->destination_order == 'desc') {
                $q->orderby('d.name', $request->destination_order);
            }

            if ($request->has('available_order') && $request->available_order == 'asc' || $request->available_order == 'desc') {
                $q->orderBy("available", $request->available_order);
            }

            if ($request->has('exclude_zero') && $request->exclude_zero != '') {
                $q->where(function ($query) {
                    $query->where('purchase_entries.available', '>', 0)
                        ->orWhere('purchase_entries.blocks', '>', 0);
                });
            }

            if ($request->has('over_booking') && $request->over_booking != '') {
                $q->where('purchase_entries.quantity', '<', 'purchase_entries.sold');
            }
            if ($request->has('show_zero') && $request->show_zero != '') {
                $q->where(function ($query) {
                    $query->where('purchase_entries.available',  0)
                        ->Where('purchase_entries.blocks', 0);
                });
            }
            if ($request->has('airline') && $request->airline != '') {
                $q->where('purchase_entries.airline_id', $request->airline);
            }
            if ($request->has('type') && $request->type != '') {
                $q->where('purchase_entries.isOnline', $request->type);
            }
            $query = $request->all();
            $request->session()->put('searchQuery', json_encode($query));
        } else {
            if ($request->has('destination_order') && $request->destination_order == 'asc' || $request->destination_order == 'desc') {
                $q->orderby('d.name', $request->destination_order);
            }
            $q->whereDate('purchase_entries.travel_date', Carbon::now()->addDay());
            $request->session()->forget(['previous_day_date', 'next_day_date']);
        }
        $limit = 100;

        if ($request->has('result') && $request->result != '') {
            $limit =  $request->result;
        }

        $data = $q->select(
            'purchase_entries.id',
            'a.name as airline_name',
            'purchase_entries.namelist_status',
            'purchase_entries.flight_no',
            'd.name as destination_name',
            'purchase_entries.pnr',
            'purchase_entries.quantity',
            'purchase_entries.airline_id',
            'purchase_entries.available',
            'purchase_entries.blocks',
            'purchase_entries.cost_price',
            'purchase_entries.sell_price',
            'purchase_entries.infant',
            'purchase_entries.isOnline',
            'purchase_entries.isRefundable',
            'purchase_entries.travel_date',
            'purchase_entries.departure_time',
            'purchase_entries.arrival_time',
            'purchase_entries.name_list',
            'o.name as owner_name',
            'o.is_third_party as owner_type',
            'purchase_entries.flight_route',
            'purchase_entries.trip_type',
            DB::raw('(select type from purchase_entry_statuses where purchase_entry_id  =   purchase_entries.id   order by id desc limit 1) as FlightStatus')
        )
            ->simplePaginate($limit);


        return view('flight-tickets.bookings.index', compact('airports', 'flight_no', 'data', 'destinations', 'airlines', 'owners', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'book_ticket_id' => 'required'
        ]);
        // return "book";
        $data = \App\PurchaseEntry::find($request->book_ticket_id);
        $agents = Agent::orderBy('company_name', 'ASC')->where('status', 1)->get();



        if (!($data->quantity >= 1)) {
            $request->session()->flash('error', 'No Inventory Available');
            return redirect(route('bookings.index'));
        }


        return view('flight-tickets.bookings.create', compact('data', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_id' => 'required|integer',
            'agent_id' => 'required|integer',
            'title' => 'required|array',
            'first_name' => 'required|array',
            'last_name' => 'required|array',
            'infant_detail' => 'sometimes',
            'remarks' => 'required',
            'pax_cost' => 'required',
        ]);

        $ticket_info = \App\PurchaseEntry::find($request->ticket_id);

        $agent_info  = Agent::find($request->agent_id);

        $adult_pax = count($request->title);

        if ($request->has('child_title'))
            $child_pax = count($request->child_title);
        else
            $child_pax = 0;

        if ($request->has('infant_title'))
            $infant_pax = count($request->infant_title);
        else
            $infant_pax = 0;
        // check quantity of the ticket otherwise throw error
        $available_ticket  = $ticket_info->available;

        if (!($available_ticket >= ($adult_pax + $child_pax))) {
            $request->session()->flash('error', 'Sorry we could not process your booking as inventory is over');
            return redirect(route('bookings.index'));
        }
        // check  agent balance
        $total_pax = $adult_pax + $child_pax;

        $current_balance    = $this->getAgentBalance($agent_info->id);
        $ticket_total_price = ($adult_pax * $request->pax_cost) + ($child_pax * $request->child_cost) + ($infant_pax * $request->infant_charge);
        $ticket_total_cost_price = ($adult_pax * $ticket_info->cost_price) + ($child_pax * $ticket_info->child_charge) + ($infant_pax * $ticket_info->infant_charge);

        if ($current_balance < $ticket_total_price)
            return "Your current balance is Rs. $current_balance and ticket price is Rs. $ticket_total_price which is less";

        $serialNo = SerialCounter::where('name', 'sale')->first();


        $next_serial = $serialNo->count + 1;

        $sessionCurrentSerialNo =  Cache::get('currentSerialNo');


        if ($sessionCurrentSerialNo == $next_serial) {
            $next_serial  = $sessionCurrentSerialNo + 1;
            Cache::put('currentSerialNo',  $next_serial);
        } else {
            Cache::put('currentSerialNo',  $next_serial);
        }

        $serialNo->update(['count' => $next_serial]);
        $travel_date = ($ticket_info->travel_date)->format('Y-m-d');
        $travel_date_time = Carbon::parse($travel_date . ' ' . $ticket_info->departure_time);

        $arrival_date = ($ticket_info->arrival_date)->format('Y-m-d');
        $arrival_date_time = Carbon::parse($arrival_date . ' ' . $ticket_info->arrival_time);
        $book_ticket_data = [
            'bill_no' => $this->generateBookingReferenceNo(),
            'agent_id' => $agent_info->id,
            'destination_id' => $ticket_info->destination_id,
            'purchase_entry_id' => $ticket_info->id,
            'destination' => $ticket_info->destination->name,
            'src'        => $ticket_info->destination->origin->code,
            'dest'       => $ticket_info->destination->destination->code,
            'pnr' => $ticket_info->pnr,
            'adults' => $adult_pax,
            'infants' => $infant_pax,
            'child' => $child_pax,
            'pax_price' => $request->pax_cost,
            'child_charge' => $request->child_cost,
            'infant_charge' => $request->infant_charge,
            'travel_date' => $ticket_info->travel_date,
            'travel_time' => $ticket_info->departure_time,
            'arrival_time' => $ticket_info->arrival_time,
            'airline' =>  $ticket_info->flight_no,
            'remark' => $request->remarks,
            'total_amount' => $ticket_total_price,
            'display_price' => $request->display_price,
            'departureDate'      => $travel_date_time,
            'arrivalDate'        => $arrival_date_time,
            'cost_price'        => $ticket_total_cost_price,
            'owner_id'          => $ticket_info->owner_id,
            'booking_source'    => BookTicketBookingSourceEnum::BACKEND_PORTAL,
            'status'            => BookTicketStatusEnum::CONFIRMED,
            'created_by' => Auth::id(),
        ];
        // dd($book_ticket_data);

        $new_book_ticket_obj = BookTicket::create($book_ticket_data);


        //   modify  the available
        $ticket_info->increment('sold', $total_pax);
        $ticket_info->decrement('available', $total_pax);

        foreach ($request->title as $key => $value) {
            $book_ticket_detail_data = [
                'book_ticket_id' => $new_book_ticket_obj->id,
                'title' => $value,
                'first_name' => $request->first_name[$key],
                'last_name' => $request->last_name[$key],
                'type' => 1, // adult
                'price' => $request->pax_cost,
            ];
            $book_ticket_detail = BookTicketSummary::create($book_ticket_detail_data);
        }
        if ($child_pax > 0) {
            foreach ($request->child_title as $key => $value) {
                $book_ticket_detail_data = [
                    'book_ticket_id' => $new_book_ticket_obj->id,
                    'title' => $value,
                    'first_name' => $request->child_first_name[$key],
                    'last_name' => $request->child_last_name[$key],
                    'price' => $request->child_cost,
                    'type' => 2 // child
                ];
                $book_ticket_detail = BookTicketSummary::create($book_ticket_detail_data);
            }
        }
        if ($infant_pax > 0) {

            foreach ($request->infant_title as $key => $value) {
                $adultKey = (int)$request->infant_travelling_with[$key];
                $passengerKey = $adultKey - 1;

                $travelling_with  = $request->first_name[$passengerKey] . ' ' . $request->last_name[$passengerKey];
                $book_ticket_detail_data = [
                    'book_ticket_id' => $new_book_ticket_obj->id,
                    'title' => $value,
                    'first_name' => $request->infant_first_name[$key],
                    'last_name' => $request->infant_last_name[$key],
                    'dob' => $request->infant_dob[$key],
                    'price' => $request->infant_charge,
                    'travelling_with' => $travelling_with,
                    'type' => 3 // infant
                ];
                $book_ticket_detail = BookTicketSummary::create($book_ticket_detail_data);
            }
        }

        $old_credit = Credits::where('agent_id', $request->agent_id)
            ->whereIn('type', [1, 2, 3, 4, 5, 6])
            ->orderBy('id', 'DESC')
            ->first();
        $old_balance = 0;
        if ($old_credit) {
            $old_balance = $old_credit->balance;
        }
        $new_balance = $old_balance - $ticket_total_price;

        // Generate Account Transaction
        $accountTransactionData = [
            'agent_id' => $agent_info->id,
            'type' => 2,
            'amount' => $ticket_total_price,
            'balance' => $new_balance,
            'owner_id' => Auth::id(),
            'reference_no' => CreditService::generateReferenceNo(),
            'ticket_id' => $new_book_ticket_obj->id
        ];



        Credits::create($accountTransactionData);
        $this->updateAgentOpeningBalance($agent_info->id, 2, $ticket_total_price);




        //Increase balance of the supplier
        $opening_bal = 0;
        $owner = Owner::find($ticket_info->owner_id);
        if ($owner->opening_balance) {
            $opening_bal = $owner->opening_balance;
        }
        $new_balance = $opening_bal + $ticket_total_price;
        $owner->opening_balance =  $new_balance;
        $owner->save();


        $markups = DB::table('airline_markups')
            ->where('agent_id', $agent_info->id)
            ->where('airline_id', $ticket_info->airline_id)
            ->where('status', 1)
            ->get();

        $additional_price = 0;

        if (isset($markups[0])) {
            $additional_price = $markups[0]->amount;
        }

        $total_markup = ($additional_price * $adult_pax) + ($additional_price * $child_pax);

        $supplierTransactionData = [
            'supplier_id' => $ticket_info->owner_id,
            'type' => 1,
            'amount' => $ticket_total_price - $total_markup,
            'balance' => $new_balance,
            'owner_id' => Auth::id(),
            'reference_no' => SupplierService::generateReferenceNo(),
            'ticket_id' => $new_book_ticket_obj->id
        ];

        SupplierTransaction::create($supplierTransactionData);
        $passenger_info  = BookTicketSummary::where('book_ticket_id', $new_book_ticket_obj->id)->get();

        try {
            if ($owner->is_third_party) {
                Mail::to($owner->email)
                    ->cc(['support@vishaltravels.in'])
                    ->send(new SupplierBookingConfirmation($new_book_ticket_obj, $passenger_info));
            }
        } catch (\Exception $e) {
            Log::info("EMail failed for " . $new_book_ticket_obj->id);
            Log::info($e);
        }


        if ($book_ticket_detail && $new_book_ticket_obj) {
            $request->session()->flash('success', 'Successfully Booked Ticket');
        } else {
            $request->session()->flash('error', 'Opps Something went wrong');
        }

        $request->session()->flash('success', 'Successfully Updated');
        $query = $request->session()->get('searchQuery');


        if ($query) {
            $decoded_query = json_decode($query);
            $array = json_decode(json_encode($decoded_query), true);
            $array['show-ticket'] = $new_book_ticket_obj->id;
            return redirect(route('bookings.index', $array));
        } else {
            return redirect(route('bookings.index', [
                'show-ticket' => $new_book_ticket_obj->id
            ]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book_summary =  BookTicket::find($id);
        $agentInfo    = Agent::find($book_summary->agent_id);
        $purchaseEntry = PurchaseEntry::find($book_summary->purchase_entry_id);
        $owner         = Owner::find($purchaseEntry->owner_id);

        if (!($request->has('pax_cost') &&
            $request->has('child_cost') &&
            $request->has('infant_charge') &&
            $request->has('agent_id')
        )) {
            if ($request->remarks == $book_summary->remark) {
                $request->session()->flash('error', 'Nothing to update');
                return redirect()->back();
            }
        } else {
            if (
                $request->pax_cost == $book_summary->pax_price &&
                $request->child_cost == $book_summary->child_charge &&
                $request->infant_charge == $book_summary->infant_charge &&
                $request->agent_id == $book_summary->agent_id &&
                $request->remarks == $book_summary->remark
            ) {
                $request->session()->flash('error', 'Nothing to update');
                return redirect()->back();
            }
        }

        if (
            $request->has('pax_cost') &&
            $request->has('child_cost') &&
            $request->has('infant_charge') &&
            $request->has('agent_id')
        ) {
            if (
                $request->pax_cost != $book_summary->pax_price ||
                $request->child_cost != $book_summary->child_charge ||
                $request->infant_charge != $book_summary->infant_charge ||
                $request->agent_id != $book_summary->agent_id
            ) {
                /*
                    ----- logic to update the price of the ticket ---
                   1. bookticket update the new price of pax_cost, child_charge,infant_charge
                   2. account_transaction amount need to be updated
                   3. owner opening_balance update
                   4. agent opening_balance update
                   5. supplier transaction update
                */
                try {

                    DB::beginTransaction();
                    $credit_query = Credits::where('ticket_id', $id)
                        ->where('type', 2)
                        ->where('agent_id', $book_summary->agent_id);


                    if (
                        $request->pax_cost != $book_summary->pax_price ||
                        $request->child_cost != $book_summary->child_charge ||
                        $request->infant_charge != $book_summary->infant_charge
                    ) {
                        $old_total_amount = ($book_summary->pax_price * $book_summary->adults)
                            + ($book_summary->child_cost * $book_summary->child)
                            + ($book_summary->infant_charge * $book_summary->infants);

                        $new_total_amount = ($request->pax_cost * $book_summary->adults)
                            + ($request->child_cost * $book_summary->child)
                            + ($request->infant_charge * $book_summary->infants);

                        if ($old_total_amount > $new_total_amount) {
                            $difference =  $old_total_amount - $new_total_amount;
                            $owner->decrement('opening_balance', $difference);
                            if ($request->agent_id == $book_summary->agent_id) {
                                $agentInfo->increment('opening_balance', $difference);
                            }
                        } elseif ($old_total_amount < $new_total_amount) {
                            // less opening_balance to agent and more opening_balance to supplier
                            $difference = $new_total_amount - $old_total_amount;
                            $owner->increment('opening_balance', $difference);
                            if ($request->agent_id == $book_summary->agent_id) {
                                $agentInfo->decrement('opening_balance', $difference);
                            }
                        }

                        $book_summary->update([
                            'pax_price' => $request->pax_cost,
                            'child_charge' => $request->child_cost,
                            'infant_charge' => $request->infant_charge
                        ]);

                        $credit_query->update([
                            'amount' => $new_total_amount
                        ]);

                        SupplierTransaction::where('ticket_id', $id)
                            ->where('type', 1)
                            ->where('supplier_id', $owner->id)
                            ->update(['amount' => $new_total_amount]);
                    } else {
                        $new_total_amount =  $old_total_amount = ($book_summary->pax_price * $book_summary->adults)
                            + ($book_summary->child_cost * $book_summary->child)
                            + ($book_summary->infant_charge * $book_summary->infants);
                    }


                    if ($request->agent_id != $book_summary->agent_id) {
                        // increase the old agent opening balance
                        // reduce the balance of new agent
                        $new_agent = Agent::find($request->agent_id);
                        $new_agent_opening_balance = $new_agent->opening_balance;

                        if ($new_agent_opening_balance < $new_total_amount)
                            return "Your current balance is Rs. $new_agent_opening_balance and ticket price is Rs. $new_total_amount which is less";


                        // old agent increase the previous balance
                        $agentInfo->increment('opening_balance', $old_total_amount);
                        // new agent increase the previous balance
                        $new_agent->decrement('opening_balance', $new_total_amount);

                        $book_summary->update([
                            'agent_id' => $request->agent_id
                        ]);
                        $credit_query->update([
                            'agent_id' => $request->agent_id
                        ]);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    report($e);
                    DB::rollback();
                }
            }
        }
        if ($request->remarks != $book_summary->remark) {
            $book_summary->update([
                'remark' => $request->remarks
            ]);
        }

        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function excel(Request  $request)
    {
        $data = new BookTicketExport($request);
        if ($data->collection()->isEmpty()) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        }
        return Excel::download($data, 'bookings-export.xlsx');
    }

    private function getAgentBalance($Agentid)
    {
        $agent = Agent::find($Agentid);
        $total_balance = 0;
        if ($agent->opening_balance > 0) {
            $total_balance = $total_balance + $agent->opening_balance;
            $total_balance = $total_balance + $agent->credit_balance;
        } else {
            $total_balance = $total_balance + $agent->credit_balance;
        }
        return $total_balance;
    }


    private  function updateAgentOpeningBalance($agent_id, $type, $amount)
    {

        $agent =  Agent::where('id', $agent_id)->first();
        $credit_balance = $agent->credit_balance;
        $balance        = $agent->opening_balance;

        if ($type == '1') {
            $amount = $agent->opening_balance + $amount;
        } elseif ($type == '2') {
            if ($balance >= $amount) {
                $balance = $balance - $amount;
                $credit_balance = $agent->credit_balance;
            } elseif ($balance < $amount && $agent->opening_balance > 0) {
                $balance    = $balance - $amount;
                $credit_balance = ($agent->credit_balance > 0) ?  $agent->credit_balance - abs($balance) : $agent->credit_balance;
            } elseif ($balance <= 0) {
                $balance        = $balance - $amount;
                $credit_balance = ($agent->credit_balance > 0) ? $agent->credit_balance - $amount : $agent->credit_balance;
            }
        } elseif ($type == 3) {
            $amount = $agent->opening_balance + $amount;
        }

        return $agent->update([
            'opening_balance' => $balance,
            'credit_balance' => $credit_balance
        ]);
    }



    public function bulkFareManagement(Request $request)
    {
        $type   = $request->type;
        $status = $request->status;
        if ($type) {
            if ($type == '1') {     //fixed
                $price = $request->price;
                $request->session()->put('type', $type);
                $request->session()->put('price', 'value');
                foreach ($request->purchase_entry_id as $key => $val) {
                    //create history
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    // update the  purchase entry date with current price
                    $history_data = [
                        'new_price' => $price,
                        'purchase_entry_id' => $purchase_entry_id,
                        'owner_id' => Auth::id()
                    ];
                    PurchaseTicketFareLog::create($history_data);
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'sell_price' => $price,
                        'child' => $price
                    ]);
                }
            } elseif ($type == '2') {  //markup
                $price = $request->price;
                foreach ($request->purchase_entry_id as $key => $val) {
                    $purchase_entry_id = $request->purchase_entry_id[$key];
                    $cost_price = $request->cost_price[$key];
                    $new_sell_price = $cost_price + $price;
                    // update the  purchase entry date with current price
                    $history_data = [
                        'new_price' => $new_sell_price,
                        'purchase_entry_id' => $purchase_entry_id,
                        'owner_id' => Auth::id()
                    ];
                    PurchaseTicketFareLog::create($history_data);
                    $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                    $purchase_entry->update([
                        'sell_price' => $new_sell_price,
                        'child' => $new_sell_price
                    ]);
                }
            }
            $request->session()->flash('success', 'Successfully updated the price');
        } elseif ($status) {
            foreach ($request->purchase_entry_id as $key => $val) {
                //create history
                $purchase_entry_id = $request->purchase_entry_id[$key];
                $purchase_entry = PurchaseEntry::find($purchase_entry_id);
                $purchase_entry->update([
                    'isOnline' => $status
                ]);
            }
            $request->session()->flash('success', 'Successfully updated the status');
        }

        return redirect()->back();
    }

    private function generateBookingReferenceNo()
    {

        $serialNo = SerialCounter::where('name', 'sale')->first();

        $next_serial = $serialNo->count + 1;

        $sessionCurrentSerialNo =  Cache::get('currentSerialNo');


        if ($sessionCurrentSerialNo == $next_serial) {
            $next_serial  = $sessionCurrentSerialNo + 1;
            Cache::put('currentSerialNo',  $next_serial, now()->addMinutes(1));
        } else {
            Cache::put('currentSerialNo',  $next_serial, now()->addMinutes(1));
        }

        $serialNo->update(['count' => $next_serial]);



        // ✅ Convert to base36 (0-9 + A-Z), then uppercase and pad to 8 chars
        $alphaNum = strtoupper(base_convert($next_serial, 10, 36));
        $formatted = str_pad($alphaNum, 8, '0', STR_PAD_LEFT);
        return  'VT-' . $formatted;
    }
}
