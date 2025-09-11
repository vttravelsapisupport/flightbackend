@extends('layouts.app')
@section('title','Supplier Commissions')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="card-title text-uppercase">Supplier Commissions</h4>
                    <p class="card-description">
                       Supplier Commission
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    @can('supplier_payment create')
                    <a href="{{ route('supplier-commissions.create') }}" class="btn btn-sm btn-primary"> New Commission</a>
                    @endcan
                </div>
            </div>
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select required name="agent_id" id="agent-select2" class="form-control   form-control-sm select2">
                            @if($agent)
                                <option value="{{$agent->id}}">{{'SID - ' . $agent->id .' '. $agent->name . ' ' . $agent->mobile . ' BL '. $agent->opening_balance}}</option>
                            @endif
                            <option value="">Select Supplier</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input required type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input required type="text" class="form-control  form-control-sm  datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                    </div>
                </div>

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
                            <th>Reference No</th>
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
                                <a href="">{{ $value->supplier_bank->bank_account_no  }}</a>
                            </td>
                            <td>
                                {{ $value->amount  }}
                            </td>
                            <td>
                                {{ Str::limit($value->transaction_id,50,'....')  }}
                            </td>
                            <td>
                                {{ Str::limit($value->remarks,100) }}
                            </td>
                            <td>
                                {{ $value->created_at->format('d-m-Y h:i:s') }}
                            </td>
                            <td>
                              <a href="">{{ $value->created_details->first_name }}</a>
                            </td>
                        </tr>
                        @endforeach
                        @endif


                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            {{ $datas->appends(request()->except('page'))->links() }}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let start_date =   $('#start_date').val();
    let end_date   =   $('#end_date').val();
    if(!start_date && !end_date ){
        let today = new Date();
        let date1 = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        $('#end_date').val(date1);

        let today1       = new Date()
        let days         = 86400000
        let sevenDaysAgo = new Date(today1 - (30*days))
        let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
        $('#start_date').val(date2);
    }



    $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
    $('.select2').select2({

    });
    $('#start_date').change(function() {
        let from = $('#start_date').val();
        $('#end_date').val(from);
    })

    $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/ajax/search/supplier',
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
