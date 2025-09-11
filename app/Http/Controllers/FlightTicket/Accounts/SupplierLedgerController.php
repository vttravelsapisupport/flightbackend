<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Exports\SupplierLedgerExport;
use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Accounts\FY;
use App\Models\FlightTicket\Accounts\SupplierTransaction;
use App\Models\FlightTicket\Accounts\SupplierOpeningBalanceFY;
use App\Models\FlightTicket\Owner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SupplierLedgerController extends Controller
{

    public function __construct() {
        $this->middleware('permission:supplier-ledger show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        ini_set('memory_limit', '640M');

        $data = [];
        $agent = null;
        // $financial_year = FY::OrderBy('id', 'DESC')->get();

        $date_from = ($request->start_date) ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $date_end =  ($request->end_date) ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay() ;

        if ($request->has('fys_id') && $request->fys_id != '') {
            $FY = FY::find($request->fys_id);
            $PreviousYearFY = FY::find(($request->fys_id-1));
            if($PreviousYearFY){
                $PreviousYearFYId = $PreviousYearFY->id;
            }else{
                $PreviousYearFYId =  $FY->id;
            }

        }else{
            $FY = FY::where('isActive' , 1)->first();
            $PreviousYearFYId  =$FY->id;
        }
        $current_fy_start = Carbon::parse($FY->financial_year_start)->startOfDay();

        if((strtotime($date_from) < strtotime($current_fy_start)) || (strtotime($date_end) < strtotime($current_fy_start))) {
            $request->session()->flash('error','Invalid Date Range');
            return redirect()->back();
        }



        $owners = Owner::where('status', 1)->get();

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $opening_balance =  $balance = $this->getFYSOpeningBal($request->supplier_id , $PreviousYearFYId);


            // $this->validate($request,[
            //     'start_date' => 'required',
            //     'end_date' => 'required'
            // ]);
            $owner = Owner::find($request->supplier_id);

            $q = SupplierTransaction::orWhereIn('supplier_transactions.type', [1, 2, 3, 9, 10])
                        ->leftJoin('book_tickets','book_tickets.id','=','supplier_transactions.ticket_id')
                        ->where('supplier_transactions.supplier_id',$request->supplier_id)
                        ->whereBetween('supplier_transactions.created_at',[$current_fy_start , $date_end]);


            $datas =  $q
            ->select('supplier_transactions.id',
            'supplier_transactions.created_at',
            'supplier_transactions.type',
            'supplier_transactions.ticket_id',
            'supplier_transactions.amount',
            'supplier_transactions.remarks',
            'supplier_transactions.payment_mode',
            'book_tickets.bill_no',
            'book_tickets.travel_date',
            'book_tickets.airline',
            DB::raw('(book_tickets.adults+book_tickets.child+book_tickets.infants) as pax_count'),
            DB::raw('(SELECT concat(title," ",first_name," ",last_name) as pax_name from book_ticket_details where book_ticket_id = book_tickets.id LIMIT 1 ) as pax_name'),
            'book_tickets.destination',
            'book_tickets.pnr',

            )->orderBy('supplier_transactions.created_at', 'asc')->get();


            $results = [];




            if(!empty($datas)) {
                foreach($datas as $item){

                    if ($item->type == 1 || $item->type == 3) { // Air ticket , Additional service
                        $item->credit = $item->amount;
                        $balance = $balance + $item->amount;
                    }

                    if ($item->type == 2) { // Refund
                        $item->debit = $item->amount;
                        $balance = $balance - $item->amount;
                    }


                    if ($item->type == 9 || $item->type == 10) { // Payment or Commission
                        $item->debit = $item->amount;
                        $balance = $balance - $item->amount;
                    }

                    $item->balance = $balance;

                    if(strtotime($item->created_at) >= strtotime($date_from) ) {
                        array_push($results, $item);
                    }
                }
            }

            $datas = collect($results);

        } else {
            $opening_balance = "false";

            $datas = collect([]);
        }
        $opening_balance_date= $FY->financial_year_start;
        return view('accounts.supplier-ledger.index', compact('datas', 'owners','opening_balance','opening_balance_date'));
    }



    public function excel_agent_ledger(Request $request){
        // $data = [];
        // $agent = null;
        // $financial_year = FY::OrderBy('id', 'DESC')->get();

        $date_from = ($request->start_date) ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $date_end =  ($request->end_date) ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay() ;

        if ($request->has('fys_id') && $request->fys_id != '') {
            $FY = FY::find($request->fys_id);
        }else{
            $FY = FY::where('isActive' , 1)->first();
        }

        $current_fy_start = Carbon::parse($FY->financial_year_start)->startOfDay();

        if(strtotime($date_from) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        if(strtotime($date_end) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        // $owners = Owner::where('status', 1)->get();
        $results = []; // this needs to be on this level - arghadip evil inc.

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $owner = Owner::find($request->supplier_id);
            $q = SupplierTransaction::orWhereIn('type', [1, 2, 3, 9]);
            $q->where('supplier_id',$request->supplier_id);
            $q->whereBetween('created_at',[$current_fy_start , $date_end]);
            $datas =  $q->orderBy('created_at', 'asc')->get();

            $balance = $this->getFYSOpeningBal($request->supplier_id , $FY->id);

            if(!empty($datas)) {
                foreach($datas as $item) {
                    if($item->ticket) {
                        if ($item->type == 1 || $item->type == 3) { // Air ticket , Additional service
                            $balance = $balance + $item->amount;
                        }

                        if ($item->type == 2) { // Refund
                            $balance = $balance - $item->amount;
                        }
                    }

                    if ($item->type == 9) { // Payment
                        $balance = $balance - $item->amount;
                    }

                    $item->balance = $balance;

                    if(strtotime($item->created_at) >= strtotime($date_from) ) {
                        array_push($results, $item);
                    }
                }
            }
        }

        if (empty($results)) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        } else {
            return Excel::download(new SupplierLedgerExport($results), 'agent-ledger-export.xlsx');
        }
    }



    function getFYSOpeningBal($supplier_id , $fys_id) {
        $query = "SELECT * FROM supplier_opening_balance_f_y_s SOB, f_y_s FY
                  WHERE SOB.fys_id = FY.id AND FY.id = $fys_id AND
                  SOB.supplier_id = $supplier_id";

        $res = DB::select(DB::raw($query));

        return isset($res[0]) ? $res[0]->amount : 0;
    }



    public function calculateSupplierLedger(Request $request) {

        $offset = $request->get('offset');
        $limit = $request->get('limit');

        $owners = Owner::whereIn('is_third_party', [1,2])->offset($offset)->limit($limit)->pluck('id');
        $balance_data = [];

        $start_date = Carbon::parse('2022-04-01')->startOfDay();
        $end_date   = Carbon::parse('2023-03-31')->endOfDay();

        foreach($owners as $owner_id) {
            $q = SupplierTransaction::orWhereIn('type', [1, 2, 3, 9, 10]);
            $q->where('supplier_id', $owner_id);
            $q->whereBetween('created_at',[$start_date , $end_date]);
            $datas =  $q->orderBy('created_at', 'asc')->get();
            $balance = $this->getFYSOpeningBal($owner_id , 4);

            if(!empty($datas)) {
                foreach($datas as $item) {
                    if($item->ticket) {
                        if ($item->type == 1 || $item->type == 3) { // Air ticket , Additional service
                            $balance = $balance + $item->amount;
                        }
                        if ($item->type == 2) { // Refund
                            $balance = $balance - $item->amount;
                        }
                    }
                    if ($item->type == 9 || $item->type == 10) { // Payment or Commission
                        $balance = $balance - $item->amount;
                    }
                }
            }

            $temp = [];
            $temp['fys_id'] = 5;
            $temp['supplier_id'] = $owner_id;
            $temp['amount'] = $balance;
            $temp['isActive'] = 1;
            $temp['created_at'] = date('Y-m-d H:i:s');
            $temp['updated_at'] = date('Y-m-d H:i:s');
            array_push($balance_data, $temp);
        }

        if(count($balance_data) > 0) {
            SupplierOpeningBalanceFY::insert($balance_data);
        }
        echo "Success";
    }



    public function getApiVendorLedger(Request $request)
    {

        $data = [];

        $agent = null;

        $financial_year = FY::OrderBy('id', 'DESC')->get();

        $date_from = ($request->start_date) ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $date_end =  ($request->end_date) ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay() ;

        if ($request->has('fys_id') && $request->fys_id != '') {
            $FY = FY::find($request->fys_id);
        }else{
            $FY = FY::where('isActive' , 1)->first();
        }

        $current_fy_start = Carbon::parse($FY->financial_year_start)->startOfDay();

        if(strtotime($date_from) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        if(strtotime($date_end) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        $owners = Owner::where('is_third_party', 2)->get();

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $owner = Owner::find($request->supplier_id);
            $q = SupplierTransaction::orWhereIn('type', [1, 2, 3, 9, 10]);
            $q->where('supplier_id',$request->supplier_id);
            $q->whereBetween('created_at',[$current_fy_start , $date_end]);
            $datas =  $q->orderBy('created_at', 'asc')->get();

            $balance = $this->getFYSOpeningBal($request->supplier_id , $FY->id);

            $results = [];


            if($date_from->format('Y-m-d') <= '2023-03-31' ||
                $date_end->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('accounts.supplier-ledger.api-ledger', compact('datas', 'owners', 'financial_year'));
            }
            if(!empty($datas)) {
                foreach($datas as $item) {
                    if($item->ticket) {
                        if ($item->type == 1 || $item->type == 3) { // Air ticket , Additional service
                            $balance = $balance + $item->amount;
                        }

                        if ($item->type == 2) { // Refund
                            $balance = $balance - $item->amount;
                        }
                    }

                    if ($item->type == 9 || $item->type == 10) { // Payment or Commission
                        $balance = $balance - $item->amount;
                    }

                    $item->balance = $balance;

                    if(strtotime($item->created_at) >= strtotime($date_from) ) {
                        array_push($results, $item);
                    }
                }
            }

            $datas = collect($results);

        } else {
            $datas = collect([]);
        }

        return view('accounts.supplier-ledger.api-ledger', compact('datas', 'owners', 'financial_year'));
    }



    public function excel_vendor_ledger(Request $request){

        // $data = [];
        // $agent = null;
        // $financial_year = FY::OrderBy('id', 'DESC')->get();

        $date_from = ($request->start_date) ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfDay();
        $date_end =  ($request->end_date) ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay() ;

        if ($request->has('fys_id') && $request->fys_id != '') {
            $FY = FY::find($request->fys_id);
        }else{
            $FY = FY::where('isActive' , 1)->first();
        }

        $current_fy_start = Carbon::parse($FY->financial_year_start)->startOfDay();

        if(strtotime($date_from) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        if(strtotime($date_end) < strtotime($current_fy_start)) {
            return "Invalid Date Range";
        }

        // $owners = Owner::where('status', 1)->get();

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            // $owner = Owner::find($request->supplier_id);
            $q = SupplierTransaction::orWhereIn('type', [1, 2, 3, 9]);
            $q->where('supplier_id',$request->supplier_id);
            $q->whereBetween('created_at',[$current_fy_start , $date_end]);
            $datas =  $q->orderBy('created_at', 'asc')->get();

            $balance = $this->getFYSOpeningBal($request->supplier_id , $FY->id);

            $results = [];

            if(!empty($datas)) {
                foreach($datas as $item) {
                    if($item->ticket) {
                        if ($item->type == 1 || $item->type == 3) { // Air ticket , Additional service
                            $balance = $balance + $item->amount;
                        }

                        if ($item->type == 2) { // Refund
                            $balance = $balance - $item->amount;
                        }
                    }

                    if ($item->type == 9) { // Payment
                        $balance = $balance - $item->amount;
                    }

                    $item->balance = $balance;

                    if(strtotime($item->created_at) >= strtotime($date_from) ) {
                        array_push($results, $item);
                    }
                }
            }
        }

        if (empty($results)) {
            return back()->with('error', 'Cannot export an empty data sheet.');
        } else {
            return Excel::download(new SupplierLedgerExport($results), 'api-vendor-ledger-export.xlsx');
        }
    }
}
