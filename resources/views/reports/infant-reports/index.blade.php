@extends('layouts.app')
@section('title','Infant Reports')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Infant Reports</h4>
                        <p class="card-description">Infant Reports in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                    </div>

                </div>
                <div class="mb-3">

                </div>

                <form class="forms-sample row" method="GET" action="">
                    <div class="col-md-2">
                        {{-- <select name="agent_id" id="agent_id" class="form-control form-control-sm destination">
                                <option value="">Select Agent</option>
                                @foreach ($agents-distributors as $key => $value)
                                    <option value="{{ $key->id }}" @if ($key->id == request()->query('agent_id')) selected @endif>
                        {{ ucwords($value->company_name) }}</option>
                        @endforeach
                        </select> --}}

                        <select name="agent_id" id="agent_id" class="form-control   form-control-sm select2">
                            <option value="">Select Agent</option>
                            @foreach ($agents as $key => $val)
                                <option value="{{ $val->id }}" @if ($val->id == request()->query('agent_id')) selected @endif>{{ $val->code }}
                                    {{ $val->company_name }} {{ $val->phone }} BL={{ $val->opening_balance }}
                                    CR={{ $val->credit_balance }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="destination_id" id="destination_id" class="form-control form-control-sm destination select2">
                            <option value="">Select Destination</option>
                            @foreach ($destinations as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('destination_id')) selected @endif>{{ ucwords($value->name) }}
                                    {{ $value->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="bill_no" placeholder="Enter the Bill No" value="{{ request()->query('bill_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date" autocomplete="off" placeholder="Enter the Travel Date" value="{{ request()->query('travel_date') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $key }}" @if ($key==request()->query('airline')) selected @endif>{{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="from" id="from">
                        <input type="hidden" name="to" id="to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <select name="limit" id="limit" class="form-control form-control-sm">
                            <option value="100">100</option>
                            <option value="1000">1000</option>
                            <option value="10000">10000</option>
                            <option value="100000">100000</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>

                </form>
                <div class="row mt-3">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="infantReportTable" class="table table-bordered  table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th>Action</th>
                                <th>#</th>
                                <th>Agency Name</th>
                                <th>Bill No</th>
                                <th>Destination</th>
                                <th>PNR No.</th>
                                <th>Vendor</th>
                                <th>Infant</th>
                                <th>Infant Name</th>
                                <th>Infant Travelling With</th>
                                <th>Infant Charge</th>
                                <th>Total </th>
                                <th>Travel Date</th>
                                <th>Travel Time</th>
                                <th>Airline</th>
                                <th>Booking Date & Time</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_amount = 0;
                                $total_pax = 0;
                            @endphp
                            @foreach ($data as $key => $value)
                                @php
                                    $isAdded = false;
                                    $infant_details = json_decode($value->details_data);
                                    foreach($infant_details as $k => $v) {
                                        if($v->status == 2) $isAdded = true;
                                    }


                                @endphp
                                <tr class="@if($isAdded) table-success @endif">
                                    <td>
                                        @if(!$isAdded)
                                    <button class="btn btn-sm btn-warning addedBtn"  value="{{ $value->id }}">
                                        Add
                                    </button>
                                    @endif

                                    </td>
                                    <td>{{ $key + $data->firstItem() }}</td>
                                    <td>{{ ucwords($value->company_name) }}</td>
                                    <td>{{ ucwords($value->bill_no) }}</td>
                                    <td>{{ ucwords($value->destination) }}</td>
                                    <td>{{ $value->pnr }}</td>
                                    <td
                                    @if($value->is_third_party == 1)
                                    class="bg-warning font-weight-bold"
                                    @elseif($value->is_third_party == 2)
                                        class="bg-info font-weight-bold"
                                    @endif
                                        title="
                                            @if($value->is_third_party == 1)Third Party Vendor @endif
                                            @if($value->is_third_party == 2)API Vendors @endif "
                                    >{{ $value->owner_name }}@if($value->is_third_party == 1) - (Third Party) @elseif($value->is_third_party == 2 )  - (API vendor)@endif</td>
                                    <td>{{ $value->infants }}</td>
                                    <td>@foreach($infant_details as $k => $v)
                                        {{ $v->title}}  {{ $v->first_name}}  {{ $v->last_name}}  <br>
                                        @endforeach
                                    </td>
                                    <td>    @foreach($infant_details as $k => $v)
                                             {{ $v->travelling_with}} <br>
                                             @endforeach

                                        @php
                                            $total_pax += count($infant_details);
                                            $temp_amount = count($infant_details) * $value->infant_charge;
                                            $total_amount += $temp_amount
                                        @endphp
                                    </td>
                                    <td> @money($value->infant_charge)</td>

                                    <td>
                                        @money($temp_amount)
                                    </td>
                                    <td>{{ $value->travel_date->format('d-m-Y') }}</td>
                                    <td>{{ $value->travel_time }}</td>
                                    <td>{{ $value->airline }}</td>
                                    <td>{{ $value->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $value->remark }}</td> </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total</th>
                                <th colspan="4" class="text-left">{{ $total_pax }}</th>
                                <th colspan="1" class="text-right">@money($total_amount)</th>
                                <th colspan="3"></th>
                            </tr>
                            </tfoot>
                        </table>
                        <div>
                        @if ($data->count() > 0)
                            {{ $data->appends(request()->input())->links() }}
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-infant-refund.csv";
        var showError = true;
        $("#excelDownload").click(function () {
            if ($('#infantReportTable tbody').find('tr').length == 0) {
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
                $("#infantReportTable").first().table2csv({
                    filename: filename,
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});

            @if(request()->query('from'))
                $('#dates').daterangepicker({
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                let from = '{!! request()->query('from') !!}';
                let to = '{!! request()->query('to') !!}';
                $('#from').val(from);
                $('#to').val(to);
                @else
                    $('#dates').daterangepicker({
                    startDate: moment(),
                    endDate: moment(),
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                $('#dates').val('Booking Date Range')
            @endif

            $('#dates').on('apply.daterangepicker', function(ev, picker) {
                let from = picker.startDate.format('DD-MM-YYYY');
                let to = picker.endDate.format('DD-MM-YYYY');
                // Update hidden fields
                $('#from').val(from);
                $('#to').val(to);
            });

            $('.select2').select2({

            });
            $('.addedBtn').click(function(){
                let id = $(this).val();
                $.ajax({
                    url:'/flight-tickets/ajax/infant-status-update',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id
                    },
                    success(resp){
                        console.log(resp);
                        location.reload();
                    }

                })

            });

        });
    </script>
@endsection
