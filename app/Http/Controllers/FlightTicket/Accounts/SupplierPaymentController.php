<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Accounts\SupplierPayment;
use App\Models\FlightTicket\Accounts\SupplierTransaction;
use App\Models\FlightTicket\Owner;
use App\Models\FlightTicket\Supplier;
use App\Services\SupplierService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplierPaymentController extends Controller
{

    public function __construct() {
        $this->middleware('permission:supplier_payment show', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $supplier = '';

        $q = SupplierPayment::orderby('created_at','DESC');

        if($request->has('supplier_id') && $request->supplier_id  != ''){
            $supplier = Owner::find($request->supplier_id);
            $q->where('supplier_id',$request->supplier_id);
        }

        if($request->start_date && $request->end_date){
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            $q->whereBetween('created_at',[$start_date,$end_date]);

            if($start_date->format('Y-m-d') <= '2023-03-31' ||
                $end_date->format('Y-m-d') <= '2023-03-31'
            ){
                $datas = collect([]);
                return view('accounts.supplier_payments.index',compact('supplier','datas'));
            }

        }
        $datas = $q->simplePaginate(50);

        return view('accounts.supplier_payments.index',compact('supplier','datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('accounts.supplier_payments.create');
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

        $data = [
            'supplier_id' => $request->supplier_id,
            'supplier_bank_detail_id'  => $request->supplier_bank_id,
            'amount'  => $request->amount,
            'payment_mode'  => $request->payment_mode,
            'bank_name' => '',
            'bank_account_no' => '',
            'transaction_id'  => $request->reference_no,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => Carbon::parse($request->date)
        ];


        if($request->hasFile('image')){
            $image_path = Storage::disk('s3')->put('supplier_payment',$request->image);
            $data['attachments'] =  $image_path;
        }

        $resp = SupplierPayment::create($data);

        // type = payment -9
        $owner       = Owner::find($request->supplier_id);
        if($owner->opening_balance) {
            $opening_bal = $owner->opening_balance;
        }
        $new_balance = $opening_bal - $request->amount;
        $owner->opening_balance =  $new_balance;
        $owner->save();



        $supplier_transaction  = [
            'supplier_id'  => $request->supplier_id,
            'type'         => 9,
            'supplier_bank_detail_id' => $request->supplier_bank_id,
            'amount'       => $request->amount,
            'balance'      => $new_balance,
            'remarks'      => $request->remarks,
            'owner_id'     => Auth::id(),
            'reference_no' => SupplierService::generateReferenceNo(),
            'created_at' => Carbon::parse($request->date)
        ];
        // create supplier transaction

        $resp = SupplierTransaction::create($supplier_transaction);

        // update the balance
        $request->session()->flash('success', 'Successfully Saved !');
        return redirect(route('supplier-payments.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
