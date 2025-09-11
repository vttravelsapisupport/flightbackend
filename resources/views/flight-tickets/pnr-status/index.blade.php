@extends('layouts.app')
@section('title','PNR Status')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('contents')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">PNR Status</h4>
                        <p class="card-description">Flight Ticket PNR Status in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </div>
                <form class="forms-sample row mb-3" method="GET" action="">
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
                        <input type="hidden" name="travel_date_from" id="travel_date_from">
                        <input type="hidden" name="travel_date_to" id="travel_date_to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('travel_date_from') }} - {{ request()->query('travel_date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                    </div>
                   
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm" name="search" value="1"> Search</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Entry Date</th>
                            <th>Airline</th>
                            <th>Flight No</th>
                            <th>Destination</th>
                            <th>PNR No </th>
                            <th>Qty</th>

                            <th>Cost Price</th>
                            <th>Sale Price</th>
                            <th>Travel Date </th>
                            <th>Arrival Date</th>
                            <th>DPT</th>
                            <th>ARV</th>
                            <th>Vendor</th>
                            <th>Route</th>
                            <th>Name List </th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody class="text-left">

                        @foreach ($details as $key => $value)

                            <tr class="@if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger @endif">
                                <td>{{ $key + $details->firstItem() }}</td>
                                <td>

                                    @if($value->FlightStatus)
                                        @if($value->FlightStatus == 1)
                                            <span class="badge badge-warning">IROP</span>
                                        @elseif($value->FlightStatus == 2)
                                            <span class="badge badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-success">On time</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $value->created_at->format('d-M-Y') }}</td>
                                <td>{{ ucwords($value->airline_name) }}</td>
                                <td>{{ ucwords($value->flight_no) }}</td>
                                <td>{{ ucwords($value->destination_name) }}</td>
                                <td>{{ $value->pnr }}</td>
                                <td>{{ $value->quantity }}</td>

                                <td>@money($value->cost_price)

                                </td>

                                <td>@money( $value->sell_price)</td>

                                <td>{{ $value->travel_date->format('d-M-Y') }}</td>
                                <td>@if($value->arrival_date) {{ $value->arrival_date->format('d-M-y') }} @else @endif</td>
                                <td>{{ $value->departure_time }}</td>

                                <td>{{ $value->arrival_time }}</td>
                                <td
                                    @if($value->owner_type == 2) class="bg-info font-weight-bold" @endif title="@if($value->owner_type == 1)API Vendors  @endif"
                                >{{ ucwords($value->owner_name) }}</td>
                                <td>{{ $value->flight_route }}</td>
                                <td>{{ $value->name_list->format('d-M-Y') }}</td>
                                <td>

                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a  href="{{ route('pnr-status.show',$value->id) }}" class="btn btn-sm btn-primary">Fetch PNR Details</a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="mt-3">
                        @if(count($details) > 0)
                        {{ $details->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
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
        @if(request()->query('travel_date_from'))
            $('#dates').daterangepicker({
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            let travel_date_from = '{!! request()->query('travel_date_from') !!}';
            let travel_date_to = '{!! request()->query('travel_date_to') !!}';
            $('#travel_date_from').val(travel_date_from);
            $('#travel_date_to').val(travel_date_to);
            @else
                $('#dates').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            $('#dates').val('Travel Date Range')

        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let travel_date_from = picker.startDate.format('DD-MM-YYYY');
            let travel_date_to = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#travel_date_from').val(travel_date_from);
            $('#travel_date_to').val(travel_date_to);
        });

</script>
@endsection