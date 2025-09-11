@extends('layouts.app')
@section('title','Supplier Ledger')
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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('agent-ledger.index') }}">Agent Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#home">Supplier Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('distributor-ledger.index') }}">Distributor Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('supplier-ledger.api') }}">Api Vendor Ledger</a>
                </li>
            </ul>
           <div class="tab-content" id="myTabContent">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Supplier Ledger</h4>
                        <p class="card-description">Supplier’s Ledger in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form action="">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select required name="supplier_id" id="supplier-id" class="form-control   form-control-sm select2">
                                    <option value="">Select Supplier</option>
                                    @foreach($owners as $owner)
                                        <option value="{{$owner->id}}" @if(request()->query('supplier_id')) @if(request()->query('supplier_id') == $owner->id) selected @endif @endif>{{ ' SID'.$owner->id .' - '. $owner->name . ' ' . $owner->phone .' BL '. $owner->opening_balance}} @if($owner->is_third_party) - (Third Party Supplier) @endif</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="col-md-2">
                                <select required name="fys_id" id="fys_id" class="form-control   form-control-sm select2">
                                    @foreach ($financial_year as $key => $val)
                                        <option value="{{ $val->id }}" @if ($val->id == request()->query('fys_id')) selected @elseif ($val->isActive == 1) selected  @endif>{{ $val->name }}

                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

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
                    <div class="row">
                        @if($opening_balance != "false")
                        <div class="col-lg-12 table-responsive ">
                            <table id="tableID" class="table table-bordered table-sm text-left ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order Type</th>
                                        <th>Ref. No</th>
                                        <th>Travel Date</th>
                                        <th>Sector</th>
                                        <th>PNR</th>
                                        <th>Airline</th>
                                        <th>No Of Pax</th>
                                        <th>Pax Name</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        <th>Remarks</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <th colspan="14" class="table-info text-center font-weight-bold">
                                        <a href="/settings/vendors/{{ request()->query('supplier_id')}}" target="_blank">Opening Balance as on {{ $opening_balance_date->format('d-m-Y') }}  is  @money($opening_balance)</a>
                                        </th>
                                    </tr> -->
                                    @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $value)
                                    <tr>

                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->created_at->format('d-m-Y h:i:s') }}</td>
                                        <td>
                                            @if ($value->type == 1)
                                            Air Ticket
                                            @elseif($value->type == 2)
                                            Refund
                                            @elseif($value->type == 3 )
                                            Additional Services
                                            @elseif($value->type == 9 )
                                            Payment
                                            @elseif($value->type == 10 )
                                            Commission
                                            @endif
                                        </td>
                                        @if($value->type == 1 || $value->type == 2 ||  $value->type == 3)
                                            <td>{{ $value->bill_no }}</td>
                                            <td>{{ Carbon\Carbon::parse($value->travel_date)->format('d-m-Y') }}</td>
                                            <td>{{ $value->destination }}</td>
                                            <td>{{ $value->pnr }}</td>
                                            <td>{{ $value->airline }}</td>
                                            <td>{{ $value->pax_count }}</td>
                                            <td>{{ $value->pax_name }} @if($value->pax_count > 1) +  {{$value->pax_count - 1 }} @endif</td>
                                        @elseif($value->type == 9 || $value->type == 10)
                                        <td colspan="7"></td>
                                        @endif
                                        <td>{{ $value->debit }} </td>
                                        <td>{{ $value->credit }}</td>
                                        <td>{{ $value->balance }}
                                        <td>{{ $value->remarks }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="14" class="text-center">No Result Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                        @endif
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script>
    let filename = "{{ Carbon\Carbon::now()->format('dd-mm-yyyy hh:mm:ss')}}" + ".csv";
    var showError = true;
    $("#excelDownload").click(function () {
        if ($('#tableID tbody').find('tr').length == 0) {
            if (showError) {
                $('#app').prepend(
                    $('<div/>')
                        .attr("role", "alert")
                        .addClass("alert alert-danger")
                        .text("Cannot export an empty data sheet.")
                );
                showError = false;
            }
        } else {
            $("#tableID").first().table2csv({
                filename: filename,
            });
        }
    });
</script>
<script>
$(document).ready(function() {
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
        $('#dates').val('Date Range')

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
                url: '/ajax/search/agents',
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
