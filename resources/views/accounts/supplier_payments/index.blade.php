@extends('layouts.app')
@section('title','Supplier Payments')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Supplier Payments</h4>
                    <p class="card-description">Supplier Payments in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('supplier_payment create')
                    <a href="{{ route('supplier-payments.create') }}" class="btn btn-sm btn-primary"> New Payment</a>
                    @endcan
                </div>
            </div>
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select required name="supplier_id" id="agent-select2" class="form-control   form-control-sm select2">
                            @if($supplier)
                                <option value="{{$supplier->id}}"> SID-{{$supplier->id .' '. $supplier->name . ' ' . $supplier->mobile . ' BL '. $supplier->opening_balance. ' CB ' . $supplier->credit_balance}}</option>
                            @endif
                            <option value="">Select Supplier</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                    </div>
                </div>
            </form>

        </div>

        <div class="row">
            <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Supplier Bank Details</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Reference No</th>
                            <th>Attachments</th>
                            <th>Remarks</th>
                            <th>Created At</th>
                            <th>Created By</th>

                        </tr>
                    </thead>
                    <tbody>

                        @if ($datas->count() > 0)
                        @foreach ($datas as $key => $value)
                        <tr>
                            <td>{{ 1 + $key  }} </td>
                            <td>
                                SID-{{ $value->supplier_id  }}
                            </td>
                            <td>
                                {{ $value->supplier_bank->bank_account_no  }}
                            </td>
                            <td>
                                {{ $value->amount  }}
                            </td>
                            <td>
                                 @if ($value->payment_mode == 1)
                                 Cash
                                @elseif($value->payment_mode == 2)
                                Online Transfer
                                @elseif($value->payment_mode == 3 )
                                Cash Deposit
                                @elseif($value->payment_mode == 4)
                                Agent Incentive
                                @endif
                            </td>
                            <td>
                                {{ Str::limit($value->transaction_id,50,'....')  }}
                            </td>
                            <td>
                                <a href="{{ $value->attachments }}" target="_blank" download>
                                    Download
                                </a>
                            </td>
                            <td>
                                {{ Str::limit($value->remarks,100) }}
                            </td>
                            <td>
                                {{ $value->created_at->format('d-m-Y h:i:s') }}
                            </td>
                            <td>
                              {{ $value->created_details->first_name }}
                            </td>
                        </tr>
                        @endforeach
                        @endif


                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="10">
                        @if ($datas->count() > 0)
                            {{ $datas->appends(request()->except('page'))->links() }}
                        @endif
                        </td>
                    </tr>

                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // let start_date =   $('#start_date').val();
    // let end_date   =   $('#end_date').val();
    // if(!start_date && !end_date ){
    //     let today = new Date();
    //     let date1 = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
    //     $('#end_date').val(date1);

    //     let today1       = new Date()
    //     let days         = 86400000
    //     let sevenDaysAgo = new Date(today1 - (30*days))
    //     let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
    //     $('#start_date').val(date2);
    // }

    $('.select2').select2({});

    @if(request()->query('start_date'))
        $('#dates').daterangepicker({
            showDropdowns: true,
            locale: {
                "format": "DD-MM-YYYY",
            }
        });
        let start_date = '{!! request()->query('start_date') !!}';
            let end_date = '{!! request()->query('end_date') !!}';
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
        @else
            $('#dates').daterangepicker({
            startDate: moment(),
            endDate: moment(),
            showDropdowns: true,
            locale: {
                "format": "DD-MM-YYYY",
            }
        });
    @endif

    $('#dates').on('apply.daterangepicker', function(ev, picker) {
        let start_date = picker.startDate.format('DD-MM-YYYY');
        let end_date = picker.endDate.format('DD-MM-YYYY');
        // Update hidden fields
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
    });

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
