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

                <form class="forms-sample row" method="POST" action="{{ route('agent-receipts.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="alias" class="col-sm-3 col-form-label">Agent</label>
                            <div class="col-sm-9">
                               {{ $data->agentDetails->company_name }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 col-form-label">Payment Mode</label>
                            <div class="col-sm-9">
                                @if($data->payment_mode == 1)
                                    Cash
                                @elseif($data->payment_mode == 2)
                                    Online Transfer
                                @elseif($data->payment_mode == 3)
                                    Cash Deposit
                                @elseif($data->payment_mode == 4)
                                    Agent Incentive
                                @elseif($data->payment_mode == 5)
                                    Discount
                                @else
                                Others
                                @endif

                            </div>
                        </div>
                        <div class="form-group row bankDiv" >
                            <label for="company_name" class="col-sm-3 col-form-label">Bank</label>
                            <div class="col-sm-9">
                                @if($data->bankDetails)
                                {{ $data->bankDetails->bank_name}}
                                @endif
                            </div>
                        </div>
                        <div class="form-group row bankDiv"  >
                            <label for="company_name" class="col-sm-3 col-form-label">Receipt Image</label>
                            <div class="col-sm-9">
                                <img src="{{ $data->image }}" alt="" class="img-fluid">
                            </div>

                        </div>
                        <div class="form-group row bankDiv"  >
                            <label for="contact_name" class="col-sm-3 col-form-label">Reference No</label>
                            <div class="col-sm-9">
                                {{ $data->reference_no }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-9">
                                {{ $data->amount }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Remarks</label>
                            <div class="col-sm-9">
                                {{ $data->remarks }}
                            </div>
                        </div>
                        <div class="form-group row"  >
                            <label for="company_name" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                @if($data->status == 1)
                                Approved
                                @elseif($data->status == 2)
                                Pending
                                @endif

                            </div>
                        </div>


                    </div>




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
