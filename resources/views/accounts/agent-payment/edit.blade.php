@extends('layouts.app')
@section('title','Receipts')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Receipt Create</h4>
                <p class="card-description">
                   Enter New Receipt
                </p>

                <form class="forms-sample row" method="POST" action="{{ route('receipts.update',$receipts->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="alias" class="col-sm-3 col-form-label">Agent</label>
                            <div class="col-sm-9">
                                <select name="agent_id" id="agent_id" class="form-control select2">

                                    @foreach($agents as $key => $val)
                                     <option value="{{ $val->id }}" @if($val->id == $receipts->agent_id) selected @endif>{{ $val->code  }} {{ $val->company_name  }} {{ $val->phone }} BL={{ $val->opening_balance  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 col-form-label">Payment Mode</label>
                            <div class="col-sm-9">
                                <select name="payment_mode" id="payment_mode" class="form-control" >
                                    <option value="1" @if($receipts->payment_mode == 1) selected @endif  >Cash</option>
                                    <option value="2" @if($receipts->payment_mode == 2) selected @endif >Online Transfer</option>
                                    <option value="3" @if($receipts->payment_mode == 3) selected @endif >Cash Deposit</option>
                                    <option value="4" @if($receipts->payment_mode == 4) selected @endif >Agent Incentive</option>
                                    <option value="5" @if($receipts->payment_mode == 5) selected @endif >Discount</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row bankDiv" @if($receipts->payment_mode != 2) style="display: none" @endif>
                            <label for="company_name" class="col-sm-3 col-form-label">Bank</label>
                            <div class="col-sm-9">
                                <select name="bank_id" id="bank_id" class="form-control" >
                                    <option value="">Select Bank </option>
                                    @foreach($bank_details as $key => $val)
                                    <option value="{{ $key }}" @if($receipts->bank_id == $key) selected @endif>{{ $val}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row bankDiv"  @if($receipts->payment_mode != 2) style="display: none" @endif>
                            <label for="company_name" class="col-sm-3 col-form-label">Receipt Image</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="image">
                            </div>

                        </div> -->
                        <div class="form-group row bankDiv"  @if($receipts->payment_mode != 2) style="display: none" @endif>
                            <label for="contact_name" class="col-sm-3 col-form-label">Reference No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="reference_no" placeholder="Enter the Reference No" name="reference_no" value="{{ $receipts->reference_no }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" placeholder="Enter the Amount" name="amount" value="{{ $receipts->amount }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Date</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="date" placeholder="Enter the date" name="date" value="{{ $receipts->date->format('Y-m-d')}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Remarks</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="remarks" placeholder="Enter the Remarks" name="remarks" value="{{ $receipts->remarks}}">
                            </div>
                        </div>
                        <!-- <div class="form-group row"  >
                            <label for="company_name" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select name="status" id="satus" class="form-control" >
                                    <option value="">Select Status </option>
                                    <option value="1">Approved</option>
                                    <option value="0">Pending </option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({

            });
            $('#payment_mode').change(function () {
                let val = $('#payment_mode').val();
                if(val == 2 || val == 3){
                     $('.bankDiv').show();
                }else{
                    $('.bankDiv').val("");
                    $('.bankDiv').hide();
                }
            })
        });
    </script>

@endsection
