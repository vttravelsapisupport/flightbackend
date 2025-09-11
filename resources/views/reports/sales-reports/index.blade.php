@extends('layouts.app')
@section('title','Sales Reports')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">

           @include('partials.sales-report-tab')

            <div class="tab-content" id="myTabContent">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title text-uppercase">Sales Reports</h4>
                            <p class="card-description">Sales Reports in the Appication.</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="mb-3">

                                <button class="btn btn-success" id="excelDownload" type="button">
                                    <i class="mdi mdi-file-excel"></i>Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                <!-- sales report -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="forms-sample row" method="GET" action="">
                        <div class="col-md-2">
                            <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2">
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
                            <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date_from" autocomplete="off" placeholder="Enter the Travel Date from" value="{{ request()->query('travel_date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date_to" autocomplete="off" placeholder="Enter the Travel Date to" value="{{ request()->query('travel_date_to') }}">
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
                            <select name="owner_id" id="owner_id" class="form-control form-control-sm  select2">
                                <option value="">Select Owner</option>
                                @foreach ($owners as $id => $owner)
                                    <option value="{{ $owner->id }}" @if ($owner->id == request()->query('owner_id')) selected @endif>{{ ucwords($owner->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="supplier_id" id="supplier_id" class="form-control form-control-sm  select2">
                                <option value="">Select Third Party Owner</option>
                                @foreach ($suppliers as $key => $value)
                                    <option value="{{ $value->id }}" @if ($value->id == request()->query('supplier_id')) selected @endif>{{ ucwords($value->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="from" id="from" autocomplete="off" placeholder="Booking Date from" value="{{ request()->query('from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="to" id="to" autocomplete="off" placeholder="Booking Date To" value="{{ request()->query('to') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="result" id="result" class="form-control form-control-sm  select2">
                                <option value="">No. of Result</option>
                                <option value="200" @if ("200" == request()->query('result')) selected @endif>200</option>
                                <option value="300" @if ("300" == request()->query('result')) selected @endif>300</option>
                                <option value="500" @if ("500" == request()->query('result')) selected @endif>500</option>
                                <option value="1000" @if ("1000" == request()->query('result')) selected @endif>1000</option>
                                <option value="5000" @if ("5000" == request()->query('result')) selected @endif>5000</option>
                                <option value="10000" @if ("10000" == request()->query('result')) selected @endif>10000</option>
                                <option value="100000" @if ("100000" == request()->query('result')) selected @endif>100000</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-behance btn-block btn-sm">Search</button>
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-bordered  table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Agency Name</th>
                                        <th>Agency Code</th>
                                        <th>Agency State</th>
                                        <th>Bill No</th>
                                        <th>Destination</th>
                                        <th>Destination Code</th>
                                        <th>PNR No.</th>
                                        <th>Pax Name</th>
                                        <th>Adult</th>
                                        <th>Child</th>
                                        <th>Infant</th>
                                        <th>Adult Per Pax Price</th>
                                        <th>Child Per Pax Price</th>
                                        <th>InfantPer Pax Price</th>
                                        <th>Adult Total Price</th>
                                        <th>Child Total Price</th>
                                        <th>Infant Total Price</th>

                                        <th>Total </th>
                                        <th>Markup</th>
                                        <th>Travel Date</th>
                                        <th>Travel Time</th>
                                        <th>Airline</th>
                                        <th>Flight </th>
                                        <th>Vendor</th>
                                        <th>Is Third Party</th>
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
                                    $passengers_count = count($value->passenger_details_names);
                                    $first_passenger_name = '';
                                        foreach($value->passenger_details_names as $k => $v){
                                            if($k == 0)
                                            $first_passenger_name = $v->title .' '. $v->first_name . ' ' . $v->last_name;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $key + $data->firstItem() }}</td>
                                        <td title="{{$value->company_name}}">{{ ucwords(Str::limit($value->company_name,20)) }}

                                        </td>
                                        <td> {{ $value->company_code}}</td>
                                        <td>{{ $value->company_state }}</td>
                                        <td>{{ ucwords($value->bill_no) }}</td>
                                        <td>{{ ucwords($value->destination) }}</td>
                                        <td>{{ $value->destination_code}}</td>
                                        <td>{{ $value->pnr }}</td>
                                        <td>
                                            @if($passengers_count == 1)
                                            {{$first_passenger_name }}
                                            @elseif($passengers_count > 1)
                                            {{ $first_passenger_name }}  +  {{ $passengers_count -1 }}
                                            @endif
                                        </td>

                                        <td>{{ $value->adults }}</td>
                                        <td>{{ $value->child }}</td>
                                        <td>{{ $value->infants }}</td>
                                        <td>{{ $value->pax_price }}</td>
                                        <td>@if( $value->child > 0 ){{ $value->child_charge }}@else 0 @endif</td>
                                        <td>@if( $value->infants > 0){{ $value->infant_charge }}@else 0 @endif</td>
                                        <td>{{ $value->pax_price * $value->adults }}</td>
                                        <td>@if( $value->child > 0 ){{ $value->child_charge * $value->child  }}@else 0 @endif</td>
                                        <td>@if( $value->infants > 0){{ $value->infant_charge * $value->infants }}@else 0 @endif</td>

                                        @php
                                        $money = ($value->pax_price * $value->adults)
                                        + ($value->child * $value->child_charge)
                                        + ($value->infants * $value->infant_charge);
                                        $total_amount = $total_amount + $money;
                                        $total_pax = $total_pax + $value->adults;
                                        @endphp
                                        <td> @money($money) </td>
                                        <td>{{  $value->agent_markup}}</td>
                                        <td>{{ $value->travel_date->format('d-m-Y') }}</td>
                                        <td>{{ $value->travel_time }}</td>
                                        <td>{{ $value->airline }}</td>
                                        <td>{{ $value->flight_no }}</td>
                                        <td
                                        @if($value->is_third_party == 1) class="bg-warning font-weight-bold" @endif t
                                        itle="@if($value->owner_type == 1)Third Party Vendor  @endif"
                                        > {{ ucwords($value->name) }} @if($value->is_third_party == 1) - (Third Party) @elseif($value->is_third_party == 2 )  - (API Vendor) @endif</td>
                                        <td>@if($value->is_third_party == 1)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>{{ $value->created_at->format('d-m-Y H:i:s') }}</td>

                                        <td>{{ $value->remark }}</td>
                                    </tr>
                                        @endforeach

                                </tbody>
                                <tfoot>
                                    <tr >
                                        <th colspan="5" class="text-right">Total</th>
                                        <th colspan="1" class="text-left">{{ $total_pax }}</th>
                                        <th colspan="2" class="text-right">@money($total_amount)</th>
                                        <th colspan="6"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mt-2">
                                {{ $data->appends(request()->input())->links() }}
                            </div>
                        </div>
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
<script>
    let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-sales.csv";
    var showError = true;
    $("#excelDownload").click(function () {
        if ($('#sortable-table-2 tbody').find('tr').length == 0) {
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
            $("#sortable-table-2").first().table2csv({
                filename: filename,


            });
        }
    });
</script>
<script>
    $(document).ready(function() {

        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $("#from").change(function() {
		let from = $("#from").val();

			$("#to").val(from);


	    });

        $('.select2').select2({

        });

        $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/flight-tickets/ajax/search/agents',
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
