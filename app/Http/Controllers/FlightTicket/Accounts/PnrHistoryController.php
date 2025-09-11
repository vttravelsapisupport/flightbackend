<?php

namespace App\Http\Controllers\FlightTicket\Accounts;


use App\Http\Controllers\Controller;
use App\Imports\PnrHistoryUpload;
use App\Models\FlightTicket\Accounts\PnrHistory;
use App\Models\FlightTicket\Airline;
use App\PurchaseEntry;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PnrHistoryController extends Controller
{

    public function excelPreview(){
        return view('accounts.pnr-history.excelPreview');
    }
    public function __construct() {
        $this->middleware('permission:pnr_history show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $q =  DB::table('pnr_histories')
                //  ->leftJoin('purchase_entries','purchase_entries.pnr', '=', 'pnr_histories.pnr')
                //  ->leftJoin('purchase_entries','purchase_entries.pnr', '=', 'pnr_histories.parent_pnr')

                // ->leftJoin('purchase_entries', function ($join) {
                //     $join->on('pnr_histories.parent_pnr', '=', 'purchase_entries.pnr')
                //             ->orWhere(function ($query) {
                //                 $query->on('purchase_entries.pnr', '=', 'pnr_histories.pnr')
                //                     ->whereNull('pnr_histories.parent_pnr');
                //             });
                // })

                ->leftJoin('purchase_entries','purchase_entries.pnr', '=', 'pnr_histories.active_pnr')
                ->orderBy('payment_date_1','DESC')
                ->orderBy('active_pnr','DESC')
                ->leftJoin('destinations','destinations.id', '=', 'purchase_entries.destination_id');

        $limit = isset($request->result) ? $request->result : 50;

        if($request->has('pnr_no') && $request->pnr_no != '') {
            $q->where('pnr_histories.active_pnr', $request->pnr_no)
            ->orWhere('pnr_histories.pnr',$request->pnr_no);
        }

        if ($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != '') {
            $from = date('Y-m-d', strtotime($request->start_date));
            $to   = date('Y-m-d', strtotime($request->end_date));
            $q->where(function ($query) use ($from, $to) {
                $query->whereBetween('pnr_histories.payment_date_1', [$from, $to]);
            });
        }

        if ($request->has('airline') && $request->airline != ''){
            $q->where('pnr_histories.airline_code', $request->airline);
        }

        if ($request->has('amount') && $request->amount != ''){
            $q->where('pnr_histories.amount', $request->amount);
        }

        if ($request->has('remarks') && $request->remarks != ''){
            if($request->remarks == 'BLANK') {
                $q->whereNull('pnr_histories.remarks');
            }else{
                $q->where('pnr_histories.remarks', $request->remarks);
            }
        }

        // $q->orderByRaw("STR_TO_DATE(pnr_histories.payment_date, '%Y-%m-%d'), STR_TO_DATE(pnr_histories.payment_date, '%d-%m%Y')");

        $datas = $q
        ->select('pnr_histories.payment_date','pnr_histories.payment_date_1','pnr_histories.id','pnr_histories.pnr','pnr_histories.parent_pnr','pnr_histories.passenger_name',
        'destinations.name as destination_name',
        'pnr_histories.amount','purchase_entries.quantity','purchase_entries.travel_date','purchase_entries.destination_id','pnr_histories.airline_code','pnr_histories.remarks')
        ->simplePaginate($limit);
        $airlines = Airline::select('id','name','code')->orderBy('id', 'DESC')->get();
        return view('accounts.pnr-history..index', compact('airlines',  'datas'));
    }


    public function importExcel(Request $request)
    {
        try{
            Excel::import(new PnrHistoryUpload($request->airline), $request->file('excel'));
            return redirect(route('pnr-history.index'))->with('success', 'Successfully Uploaded!');
        }catch(QueryException  $e) {
            return redirect(route('pnr-history.index'))->with('error', $e->getMessage());
        }catch(\Exception $e) {
            return redirect(route('pnr-history.index'))->with('error', $e->getMessage());
        }
    }


    public function showPnrHistory(Request $request) {
        $purchase = PurchaseEntry::find($request->id);
        $datas = Pnrhistory::where('pnr', $purchase->pnr)->orWhere('parent_pnr', $purchase->pnr)->get();

        return view('accounts.pnr-history.show', compact('datas'));
    }



    public function addRemarks(Request $request) {
        $Pnrhistory = Pnrhistory::find($request->id);
        $Pnrhistory->update(['remarks' => $request->remarks]);

        return response()->json(['success' => true, 'message' => 'Successfully Updated']);
    }
}
