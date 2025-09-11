@extends('layouts.app')
@section('title','Supplier Payments')
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
                <h4 class="card-title text-uppercase">Supplier Payments</h4>
                <p class="card-description">
                  Supplier Payment
                </p>
                <form class="forms-sample row" method="POST" action="{{ route('supplier-payments.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="alias" class="col-sm-3 col-form-label">Supplier</label>
                            <div class="col-sm-9">
                                <select name="supplier_id" id="agent-select2" class="form-control select2" required>
                                    <option value="">Select Supplier</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 col-form-label">Bank Details</label>
                            <div class="col-sm-9">
                                <select name="supplier_bank_id" id="supplier_bank_id" class="form-control" required >
                                    <option value="1"  ></option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_mode" class="col-sm-3 col-form-label">Payment Mode</label>
                            <div class="col-sm-9">
                                <select name="payment_mode" id="payment_mode" class="form-control"  required>
                                    <option value="1" selected >Cash</option>
                                    <option value="2" >Online Transfer</option>
                                    <option value="3" >Cash Deposit</option>
                                    <option value="4"> Supplier Commission</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row bankDiv"  >
                            <label for="company_name" class="col-sm-3 col-form-label">Receipt Image</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="image" id="image" required>
                            </div>

                        </div>
                        <div class="form-group row bankDiv"  >
                            <label for="contact_name" class="col-sm-3 col-form-label">Reference No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="reference_no" placeholder="Enter the Reference No" name="reference_no" value="{{ old('receipt_no') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" placeholder="Enter the Amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Date</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="date" placeholder="Enter the date" name="date" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Remarks</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="remarks" placeholder="Enter the Remarks" name="remarks" value="{{ old('remarks') }}" required>
                            </div>
                        </div>

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
            $('#agent-select2').change(function() {
                 $("#supplier_bank_id").empty();
                let current_supplier_id = $(this).val();
                console.log(current_supplier_id);
                $.ajax({
                    url: '/flight-tickets/ajax/search/supplier-bank-details',
                    data: {
                        id : current_supplier_id
                    },
                    type: 'GET',
                    success:function(resp){
                        console.log(resp)
                        var $dropdown = $("#supplier_bank_id");
                        $.each(resp, function() {
                            $dropdown.append($("<option />").val(this.id).text(this.text));
                        });
                    }

                })
            });
            $('#payment_mode').change(function () {
                let val = $('#payment_mode').val();
                if(val == 2 || val == 3){
                    $("#image").attr("required", "required");
                    $("#reference_no").attr("required", "required");
                     $('.bankDiv').show();
                }else{
                    $('.bankDiv').val("");
                    $("#image").removeAttr("required");
                    $("#reference_no").removeAttr("required");
                    $('.bankDiv').hide();
                }
            })
            $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/flight-tickets/ajax/search/supplier',
                delay: 250 ,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },

                dataType: 'json',
                cache: true
            },
            minimumInputLength: 4,
        });
        });
    </script>

@endsection
