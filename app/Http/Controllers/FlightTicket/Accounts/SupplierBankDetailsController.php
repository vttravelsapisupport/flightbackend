<?php

namespace App\Http\Controllers\FlightTicket\Accounts;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Accounts\SupplierBankDetails;
use Illuminate\Http\Request;

class SupplierBankDetailsController extends Controller
{
    public function index()
    {
        //
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
        $this->validate($request,[
            'account_holder_name' => 'required',
            'bank_name' => 'required',
            'branch' => 'required',
            'bank_account_no' => 'required',
            'ifsc_code' => 'required',
            'account_holder_name' => 'required',
            'account_verified' => 'required',
            'supplier_id' => 'required',
            'attachment_url' => 'required'
        ]);

        $data = [
            'account_holder_name' => $request->account_holder_name,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'isVerified' => $request->account_verified,
            'bank_account_no' => $request->bank_account_no,
            'ifsc_code' => $request->ifsc_code,
            'supplier_id' => $request->supplier_id,
            'attachment' => $request->attachment_url,
        ];

        $resp = SupplierBankDetails::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SupplierBankDetails  $supplierBankDetails
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierBankDetails $supplierBankDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SupplierBankDetails  $supplierBankDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierBankDetails $supplierBankDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SupplierBankDetails  $supplierBankDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierBankDetails $supplierBankDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SupplierBankDetails  $supplierBankDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierBankDetails $supplierBankDetails)
    {
        //
    }
}
