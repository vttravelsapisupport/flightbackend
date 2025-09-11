<?php

namespace App\Http\Controllers\FlightTicket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\AgentService;
use App\Models\FlightTicket\Agent;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Airline;
use App\Models\FlightTicket\Credits;

use App\Models\FlightTicket\Destination;
use Illuminate\Support\Facades\Validator;
use App\Models\FlightTicket\AirTicketRefunds;
use App\Models\FlightTicket\BookTicketSummary;
use App\Models\FlightTicket\Accounts\SupplierTransaction;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct() {
        $this->middleware('permission:refunds show', ['only' => ['index']]);
    }

    public function index(Request $request){        
        // $daterestriction = Carbon::parse("2023-03-31")->toDateString();
        //dd($daterestriction);
        // $this->validate($request,[
        //     'from' => 'date|after:'.$daterestriction,
        //     'to' => 'date|after_or_equal:from'
        // ]);
      
        $q  = AirTicketRefunds::with('bookTicket');
        // agent_id
        if($request->has('agent_id') && $request->agent_id != ''){
            $q->where('agent_id',$request->agent_id);
        }
        //bill no
        if($request->has('bill_no') && $request->bill_no != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('bill_no', '=',  $request->bill_no);
            });
        }
        //destination
        if($request->has('destination_id') && $request->destination_id != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('destination_id', '=',  $request->destination_id);
            });
        }
        //travel_date
        if($request->has('travel_date') && $request->travel_date != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->whereDate('travel_date',Carbon::parse($request->travel_date));
            });
        }
        //airline
        if($request->has('airline') && $request->airline != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('airline',$request->airline);
            });
        }
        // pnr
        if($request->has('pnr_no') && $request->pnr_no != ''){
            $q->whereHas('bookTicket', function($query) use ($q,$request){
                $query->where('pnr',$request->pnr_no);
            });
        }

        // created date and time range
        if($request->has('from') && $request->from != '' && $request->has('to') && $request->to != ''){
            $from = Carbon::parse($request->from);
            $to   = Carbon::parse($request->to)->endOfDay();
            $q->whereBetween('created_at',[$from,$to]);
        }
        $datas = $q->orderBy('id','DESC')->simplePaginate(50);

        //return $datas;
        $agents = Agent::where('status',1)->get();

        // $destinations =  Destination::pluck('name','id')->all();
        $destinations = Destination::where('status',1)->get();
        $airlines     =  Airline::pluck('name','id')->all();


        return view('flight-tickets.refunds.index',compact('datas','agents','destinations','airlines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request,$id)
    {
        $datas    = AirTicketRefunds::find($id);
        $passengers =  BookTicketSummary::find($datas->passenger_ids);


        return view('flight-tickets.refunds.view',compact('datas','passengers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $datas      = AirTicketRefunds::find($id);
        $passengers = BookTicketSummary::find($datas->passenger_ids);        
        
        return view('flight-tickets.refunds.edit',compact('datas','passengers'));
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
        $datas                     = AirTicketRefunds::find($id);
        $agentsInfo                = Agent::find($datas->agent_id);
        $pax_cost                  = $request->refund_pax_price;
        $supplier_balance          = $request->supplier_balance;
        $supplier_refund_pax_price = $request->supplier_refund_pax_price;          
                        
        // modify the opening account balance
        if($datas->total_refund >  $request->balance){
            $opening_balance = $datas->total_refund  -  $request->balance;
            $agentsInfo->decrement('opening_balance', $opening_balance);
        }else{
            // increment  opening balance
            $opening_balance = $request->balance  -  $datas->total_refund;
            $agentsInfo->increment('opening_balance', $opening_balance);

            if($agentsInfo->credit_limit > 0) {
                AgentService::updateCreditBalanceBasedOnCreditLimt($agentsInfo->id, 1, $opening_balance);
            }
        }
    
        $data = [
            'pax_cost'                  => $pax_cost,
            'supplier_refund_pax_price' => $supplier_refund_pax_price,
            'total_refund'              => $request->balance
        ];

        $refund  = $datas->update($data);
        $credits = Credits::find($datas->account_transaction_id);
        
        $credits->update(['amount' => $request->balance]);
        
        $this->updateAccountTransactionBalance($datas->agent_id);

        $supplierTrans    = SupplierTransaction::where('ticket_id', $datas->book_ticket_id)->where('type', 2)->get();
        $supplierTransObj = SupplierTransaction::find($supplierTrans[0]->id);
        
        $supplierTransObj->update(['amount' => $supplier_balance]);

        $request->session()->flash('success','Successfully Updated');
        return redirect(route('refunds.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        //  stock increase if available
        $datas      = AirTicketRefunds::find($id);
        $ticket_info  = \App\PurchaseEntry::find($datas->bookTicket->purchase_entry_id);

        if(!($ticket_info->available >= $datas->pax)){
            $request->session()->flash('error','No Available Ticket');
            return redirect(route('refunds.index'));
        }
        //decrease available with no of pax
        $ticket_info->decrement('available',$datas->pax);
        //increase sold with no of pax
        $ticket_info->increment('sold',$datas->pax);
        // agent opening balance decrease
        $agentsInfo = Agent::find($datas->agent_id);
        $agentsInfo->decrement('opening_balance', $datas->total_refund);
        // update the passenger is_redund = 0
        $passengers =  BookTicketSummary::find($datas->passenger_ids);

        foreach($passengers as $key => $val){
            $val->update([
                'is_refund' => 0
            ]);
        }
        // delete transaction
        $credits = Credits::find($datas->account_transaction_id);
        $credits->delete();
        // delete Airticket Refund
        $refund =  $datas->delete();
        // modify the balance of account transaction
        $this->updateAccountTransactionBalance($datas->agent_id);

        // Delete supplier transaction
        $supplierTrans = SupplierTransaction::where('ticket_id', $datas->book_ticket_id)->where('type', 2)->get();
        $supplierTransObj =  SupplierTransaction::find($supplierTrans[0]->id);
        $supplierTransObj->delete();

        $request->session()->flash('success','Successfully Deleted');
        return redirect(route('refunds.index'));
    }



    private  function updateAccountTransactionBalance($agent_id){
        $credits = Credits::where('agent_id',$agent_id)
            ->get();

        $balance =$credits[0]->balance;

        foreach($credits as $key => $val){
            $amount = $val->amount;

            if($val->type == 2)
                $balance = $balance - $amount;
            if($val->type == 3)
                $balance =  $balance + $amount;
            if($val->type == 4)
                $balance =  $balance + $amount;

            $val->balance = $balance;
            $val->update(['balance' => $balance]);

        }
    }
}
