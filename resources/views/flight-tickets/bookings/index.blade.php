@extends('layouts.app')
@section('title','Bookings')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .select2-results__option {
            padding: 0px !important;
        }
        tr.text-dark th{
            color: black !important;
        }
        .sticky{
            position: fixed;
            top: 4px;
            width: 75%;
            z-index: 1000000;
        }
        .parentdiv {


            z-index: 100000;
        }
        .sticky-buttons {
            background-color: #ffffff;
            position: sticky;
            width: 100%;
        }
    </style>
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Bookings</h4>
                        <p class="card-description">Offline Booked Tickets in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('purchase_entry export')
                            <button class="btn btn-sm btn-success" id="excelDownload" type="button">
                                <i class="mdi mdi-file-excel"></i>Export Excel
                            </button>
                        @endcan
                    </div>
                </div>
                <br>
                <form class="forms-sample row" method="GET" action="" id="searchForm">
                    <input type="hidden" name="available_order"  value="{{ request()->query('available_order') }}" id="available_order">
                    <input type="hidden" name="destination_order"  value="{{ request()->query('destination_order') }}" id="destination_order">

                    <div class="col-md-2 mb-2">
                        <select name="destination_id" id="destination_id"
                                class="form-control form-control-sm destination select2" style="width:100%">
                            <option value="">Select Destination</option>
                            @foreach ($destinations as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('destination_id')) selected @endif>{{ ucwords($value->name) }}
                                    {{ $value->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="travel_date_from" id="travel_date_from">
                        <input type="hidden" name="travel_date_to" id="travel_date_to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('travel_date_from') }} - {{ request()->query('travel_date_to') }}">
                    </div>

                    <div class="col-md-2  mb-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $key }}" @if ($key == request()->query('airline')) selected @endif>{{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2  mb-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm"
                               name="pnr_no" autocomplete="off" placeholder="Enter the PNR No"
                               value="{{ request()->query('pnr_no') }}">
                    </div>

                    <div class="col-md-2  mb-2">
                        <select name="flight_no" id="flight_no" class="form-control form-control-sm destination select2">
                            <option value="">Select Flight</option>
                            @foreach ($flight_no as $key => $value)
                                <option value="{{ $value->flight_no }}" @if ($value->flight_no == request()->query('flight_no')) selected @endif>
                                    {{ ucwords($value->flight_no) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2  mb-2">
                        <select name="airport_id" id="airport_id" class="form-control form-control-sm destination select2">
                            <option value="">Select Airport</option>
                            @foreach ($airports as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('airport_id')) selected @endif>
                                    {{ $value->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker"
                               name="namelist_date" id="namelist_date" autocomplete="off"
                               placeholder="Enter the Name List Date " value="{{ request()->query('namelist_date') }}">
                    </div>
                    <div class="col-md-2  mb-2">
                        <select name="type" id="type"
                                class="form-control form-control-sm destination " style="width:100%">
                            <option value="">Select Type</option>
                            <option value="2" @if (2 == request()->query('type')) selected @endif>Online</option>
                            <option value="1" @if (1== request()->query('type')) selected @endif>Offline</option>

                        </select>
                    </div>
                    <div class="col-md-2  mb-2">
                        <select name="namelist_status_id" id="namelist_status_id"
                                class="form-control form-control-sm destination " style="width:100%">
                            <option value="" selected>Select NameList Status</option>
                            <option value="1" @if (1== request()->query('namelist_status_id')) selected @endif>Partially send</option>
                            <option value="2" @if (2== request()->query('namelist_status_id')) selected @endif>Fully send</option>
                            <option value="3" @if (3== request()->query('namelist_status_id')) selected @endif>Checked</option>
                            <option value="4" @if (4 == request()->query('namelist_status_id')) selected @endif>Partially DB Check</option>
                            <option value="5" @if (5 == request()->query('namelist_status_id')) selected @endif>Fully DB Check</option>
                            <option value="6" @if (6 == request()->query('namelist_status_id')) selected @endif>Pending</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="owner_id" id="owner_id" class="form-control form-control-sm  select2">
                            <option value="">Select Owner</option>
                            @foreach ($owners as $id => $name)
                                <option value="{{ $id }}" @if ($id == request()->query('owner_id')) selected @endif>{{ 'SID'.$id .' - '. ucwords($name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="supplier_id" id="supplier_id" class="form-control form-control-sm  select2">
                            <option value="">Select Third Party Owner</option>
                            @foreach ($suppliers as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('supplier_id')) selected @endif>{{ 'SID'.$value->id .' - '. ucwords($value->name) }}
                                </option>
                            @endforeach
                        </select>
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
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="1" name="exclude_zero"
                                       @if (request()->query('exclude_zero') == 1) checked @endif>
                                Exclude Zero
                                <i class="input-helper"></i></label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="1" name="show_zero"
                                       @if (request()->query('show_zero') == 1) checked @endif>
                                Show Zero
                                <i class="input-helper"></i></label>
                        </div>
                    </div>
                    <div class="col-md-1">

                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="1" name="over_booking"
                                       @if (request()->query('over_booking') == 1) checked @endif>
                                OB
                                <i class="input-helper"></i></label>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="search" value="search">
                        <button class="btn btn-behance btn-block btn-sm"  name="search_btn" value="search">
                            Search
                        </button>
                    </div>
                </form>
                <div class="parentdiv">
                    <div class="sticky-buttons">
                        <div class="row mt-2 mb-1" >
                            <div class="col-md-8">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button class="btn btn-outline-info btn-sm ViewTicketDetailsBtn" id="viewAnchor"
                                            data-toggle="modal" data-target="#ViewTicketDetails">View</button>
                                    @can('book_ticket create')
                                        <a href="javascript:void(0)" class="btn btn-outline-success btn-sm" id="bookAnchor">Book</a>
                                    @endcan
                                    @can('block-ticket')
                                        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm" id="blockAnchor">Block</a>
                                    @endcan
                                    @can('purchase_entry update')
                                        <a href="javascript:void(0)" class="btn btn-outline-info btn-sm" id="modifyAnchor">Modify </a>
                                    @endcan
                                    @can('purchase_entry update')
                                        <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" id="statusAnchor">Status </a>
                                    @endcan
                                    @can('purchase_entry show')
                                        <a href="javascript:void(0)" class="btn btn-outline-success btn-sm" id="ticketPurchaseShowAnchor">Details </a>
                                    @endcan
                                    @can('namelist show')
                                        <a target="name_list_show" href="javascript:void(0)" class="btn btn-outline-success btn-sm" id="NameListShowAnchor">Name List </a>
                                    @endcan
                                    @can('pnr_history show')
                                        <a target="history_tab" href="javascript:void(0)" class="btn btn-outline-info btn-sm" id="PnrHistoryShowAnchor">History</a>
                                    @endcan
                                    @can('namelist show')
                                        <a target="pnr_details_tab" href="javascript:void(0)" class="btn btn-outline-primary btn-sm" id="pnr_fetch">Fetch PNR Details</a>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a
                                        href="{{ url('/flight-tickets/bookings') .
                                        '?' .
                                        http_build_query([
                                            'airline' => request()->query('airline'),
                                            'destination_id' => request()->query('destination_id'),
                                            'pnr_no' => request()->query('pnr_no'),
                                            'exclude_zero' => request()->query('exclude_zero'),
                                            'travel_date_from' => Carbon\Carbon::parse(request()->query('travel_date_to'))->subDay()->format('d-m-Y'),
                                            'travel_date_to' => Carbon\Carbon::parse(request()->query('travel_date_to'))->subDay()->format('d-m-Y'),
                                            'previous_day' => true,
                                            'search' => true,
                                        ]) }}" class="btn btn-sm btn-outline-warning">   <i class="mdi mdi-arrow-left"></i> Prev
                                        Day</a> &nbsp;

                                    <a
                                        href="{{ url('/flight-tickets/bookings') .
                                    '?' .
                                    http_build_query([
                                        'airline' => request()->query('airline'),
                                        'destination_id' => request()->query('destination_id'),
                                        'pnr_no' => request()->query('pnr_no'),
                                        'exclude_zero' => request()->query('exclude_zero'),
                                        'travel_date_from' => Carbon\Carbon::parse(request()->query('travel_date_to'))->addDay()->format('d-m-Y'),
                                        'travel_date_to' => Carbon\Carbon::parse(request()->query('travel_date_to'))->addDay()->format('d-m-Y'),
                                        'next_day' => true,
                                        'search' => true,
                                    ]) }}" class="btn  btn-sm  btn-outline-warning"> Next
                                        Day <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-bordered table-sm sortable-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th width="2%" class="slno">Sl no.</th>
                                        <th class="sortStyle">Status<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Airline<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Flight No <i class="mdi mdi-chevron-down"></i></th>
                                        <th id="destinationOrder" >Destination</th>
                                        <th class="sortStyle">PNR No.<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Trip Type</th>
                                        <th  id="AvailableQtyOrder" width="2%">Avlb Qty.</th>
                                        <th class="sortStyle" class="sortStyle">Block<i class="mdi mdi-chevron-down"></i></th>
                                        @can('booking_cost_price_show')
                                        <th class="sortStyle">CP<i class="mdi mdi-chevron-down"></i></th>
                                        @endcan
                                        <th class="sortStyle" >SP<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle" >Inf Price</th>
                                        <th class="sortStyle">Type<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Travel Date<i class="mdi mdi-chevron-down"></i></th>
                                        <th>Is Refundable</th>
                                        <th class="sortStyle">DPT<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">ARV<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Name List<i class="mdi mdi-chevron-down"></i></th>
                                        <th class="sortStyle">Vendor<i class="mdi mdi-chevron-down"></i></th>
                                        <th>Flight Type</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    @if ($data->count() > 0)
                                        @foreach ($data as $key => $value)
                                            <tr class="@if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger
                                            @elseif($value->namelist_status == 4)
                                            table-primary
                                            @elseif($value->namelist_status == 5)
                                            table-secondary
                                            @elseif($value->namelist_status == 6)
                                            table-success
                                            @endif">
                                            <td class="slno">
                                            <input type="radio" name="checked" value="{{ $value->id }}" autocomplete="off">
                                            {{ 1 + $key }}
                                            </td>

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

                                                <td>{{ ucwords($value->airline_name) }}</td>
                                                <td>{{ ucwords($value->flight_no) }}</td>
                                                <td>
                                                    {{ ucwords($value->destination_name) }}
                                                    <span class="tooltip-icon" title='Please wait..' data-id='{{$value->id}}' id='flight_{{$value->id}}'>
                                                    <i class="mdi mdi-information-outline mdi-18px"></i>
                                                    </span>
                                                    <span class=" namelist-info" title='Please wait..' data-id='{{$value->id}}' id='namelist_{{$value->id}}'>
                                                        <i class="mdi mdi-airplane-takeoff mdi-18px"></i>
                                                    </span>
                                                </td>
                                                <td>{{ $value->pnr }}</td>
                                                <td  class="">
                                                    @if($value->trip_type == 1)
                                                    <span class="badge badge-info">One Way</span>
                                                    @else
                                                    <span class="badge badge-warning">Round Trip</span>
                                                    @endif
                                                </td>
                                                <td><label class="@if ($value->available == 0) text-danger @else text-success @endif"><b>{{ $value->available }}</b></label> ({{ $value->quantity }})</td>
                                                <td>{{ $value->blocks }}</td>
                                                @can('booking_cost_price_show')
                                                <td> {{ $value->cost_price }}</td>
                                                @endcan
                                                <td>
                                                    @if(auth()->user()->can('fare_management show'))
                                                        <input
                                                            type="text"
                                                            readonly class="sellPriceInput text-center"
                                                            value="{{ $value->sell_price }}"
                                                            data="{{ $value->id }}"
                                                            style="width: 60px;"
                                                        >
                                                    <p type="hidden"style="display: none;" >{{ $value->sell_price }}</p>
                                                    @else
                                                        {{ $value->sell_price }}
                                                    @endif
                                                </td>
                                                <td>{{$value->infant}}</td>
                                                <td>
                                                    @if(auth()->user()->can('booking_offline_online_flight_ticket show'))
                                                        @if ($value->isOnline == 2)
                                                            <button
                                                                class="badge badge-success isOnlineButton"
                                                                type="button"
                                                                value="{{ $value->id }}"
                                                            >Online</button>
                                                        @elseif($value->isOnline == 1)
                                                            <button class="badge badge-info isOfflineButton"
                                                                    value="{{ $value->id }}"
                                                            >Offline</button>
                                                        @else
                                                            <span class="badge badge-danger">Not Set</span>
                                                        @endif
                                                    @else
                                                        @if ($value->isOnline == 2)
                                                            <span class="badge badge-success">Online</span>
                                                        @elseif($value->isOnline == 1)
                                                            <span class="badge badge-danger">Offline</span>
                                                        @else
                                                            <span class="badge badge-danger">Not Set</span>
                                                        @endif
                                                    @endif
                                                    <input type="hidden" value="{{ $value->sell_price }}" name="sell_price[]" id="sellprice{{ $key }}">
                                                    <input type="hidden" value="{{ $value->cost_price }}" name="cost_price[]" id="sellprice{{ $key }}">
                                                    <input type="hidden" value="{{ $value->id }}" name="purchase_entry_id[]" id="id{{ $key }}">
                                                </td>

                                                <td>{{ Carbon\Carbon::parse($value->travel_date)->format('d-m-Y') }}</td>
                                                <td>
                                                    @if ($value->isRefundable == 1)
                                                        <button
                                                            class="badge badge-success isRefundableButton"
                                                            type="button"
                                                            value="{{ $value->id }}"
                                                            @if($value->airline_id != 1) disabled @endif
                                                        >Refundable</button>
                                                    @else
                                                        <button class="badge badge-grey isNonRefundableButton"
                                                                value="{{ $value->id }}"
                                                                @if($value->airline_id != 1) disabled @endif
                                                        >Non Refundable</button>
                                                    @endif
                                                </td>
                                                <td>{{ $value->departure_time }}</td>
                                                <td>{{ $value->arrival_time }}</td>
                                                <td>{{ Carbon\Carbon::parse($value->name_list)->format('d-m-Y') }}</td>
                                                <td
                                                    @if($value->owner_type == 1)
                                                        class="bg-warning font-weight-bold"
                                                    @endif
                                                    @if($value->owner_type == 2)
                                                        class="bg-info font-weight-bold"
                                                    @endif
                                                    title="
                                                @if($value->owner_type == 1)Third Party Vendor @endif
                                                @if($value->owner_type == 2)API Vendors @endif
                                                "
                                                > {{ ucwords($value->owner_name) }}</td>
                                                <td>{{ $value->flight_route }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="14" class="text-center">No Ticket Available at
                                                {{ Carbon\Carbon::parse(request()->query('travel_date_to'))->format('m/d/Y') }}
                                            </td>

                                        </tr>
                                    @endif
                                    </tbody>
                            </table>

                            <div class="mt-3">
                                @if ($data->count() > 0)
                                {{ $data->appends(request()->except('page'))->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ViewTicketDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-2">Ticket Purchase History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-primary" role="alert" id="modalAlertMsg" style="display: none">

                    </div>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>Airline</th>
                            <th>PNR</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Flight No</th>
                            <th>DEPT</th>
                            <th>ARV</th>
                            <th>Qty</th>
                            <th>Available</th>
                            <th>Sold</th>
                            <th>Trip Type</th>
                        </tr>
                        </thead>
                        <tbody id="tableBookDetailBody">


                        </tbody>
                    </table>
                    <br>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-gradient-warning">
                        <tr class=" text-dark">
                            <th >Airline</th>
                            <th>PNR</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Flight No</th>
                            <th>DEPT</th>
                            <th>ARV</th>
                            <th>Qty</th>
                            <th>PNR Status</th>
                            <th>Flight Status</th>
                        </tr>
                        </thead>
                        <tbody id="tableBookDetailAirlineBody1">


                        </tbody>
                    </table>
                    <hr>

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Passenger Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Website Passenger Details</button>
                        </li>

                      </ul>
                      <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th>Type</th>
                                        <th>Name </th>
                                        <th>Agency</th>
                                        <th>Pax Price</th>
                                        <th>Remarks</th>
                                        <th>Agent Remarks</th>
                                        <th>Internal Remark</th>
                                        <th>Booking Date & Time</th>
                                        <th>Comments</th>

                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div id="notificationDiv"></div>
                            <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>

                                        <th>Passenger Name (P)</th>
                                        <th>Passenger Name (W)</th>
                                        <th>Gender (W)</th>
                                        <th>Type (W)</th>
                                        <th>Additional Service (W)</th>

                                    </tr>
                                    </thead>
                                    <tbody id="tableBody1">


                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showTicketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking Ticket Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('partials.notification')
                    <a href="" id="idFrameAnchor" target="_blank">Print</a>
                    <iframe src="" id="iframeID" frameborder="0" width="100%"
                            height="600px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{  asset('assets/js/jq.tablesort.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stickyHeading = document.querySelector('.sticky-buttons');
            const navbar = document.querySelector('.horizontal-menu');
            const content = document.querySelector('.parentdiv');
            const scrollThreshold = 280;

            window.addEventListener('scroll', () => {
                const navbarHeight = navbar.clientHeight;
                const scrollY = window.scrollY;

                if (scrollY > scrollThreshold) {
                    stickyHeading.style.position = 'fixed';
                    stickyHeading.style.top = '65px';
                    stickyHeading.style.zIndex = '1000';
                    stickyHeading.style.width = '97%';
                    stickyHeading.style.boxShadow = '0px 10px 10px rgba(28, 39, 60, 0.03);';
                    stickyHeading.style.borderBottom = '1px solid red;';
                    content.style.marginTop = `${stickyHeading.clientHeight}px`;
                } else {
                    stickyHeading.style.position = 'static';
                    content.style.marginTop = '0';
                    stickyHeading.style.width = '100%';
                }
            });

        });

    </script>

    <script>
        $(document).ready(function() {
            if ($('#sortable-table-2').length) {
                $('#sortable-table-2').tablesort();
            }
            $(window).scroll(function(e){
                var $el = $('#sticky-bar');
                var isPositionFixed = ($el.css('position') == 'fixed');
                if ($(this).scrollTop() > 200 && !isPositionFixed){
                    $el.addClass('sticky');
                }
                if ($(this).scrollTop() < 200 && isPositionFixed){
                    $el.removeClass('sticky');
                }
            });

            $('.select2').select2({});
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#type-select').change(function(e) {
                $('#status-select').removeAttr('required');
            });

            $('#status-select').change(function(e) {
                $('#type-select').removeAttr('required');
                $('#price').removeAttr('required');
            });

            checkQueryParam();
            $('.ViewTicketDetailsBtn').click(function(e) {
                $('#tableBody').empty();
                $('#tableBody1').empty();
                $('#tableBookDetailBody').empty();
                $('#tableBookDetailAirlineBody1').empty();
                let id = $(this).val();

                $.ajax({
                    url: '/flight-tickets/ajax/passenger-details',
                    data: {
                        id: id
                    },
                    type: 'GET',
                    success: function(resp) {
                        console.log(resp);
                        if (resp.success) {

                            let passengers_name = [

                            ];
                            let temp_psg_name =[];


                            // show the success message;
                            $('#modalAlertMsg').show();
                            $('#modalAlertMsg').html(resp.message);
                            let i = 1;
                            //find duplicate name  start
                            $.each(resp.data,function(index, value) {
                                let temp_name = value.title +' '+value.first_name +' '+ value.last_name;
                                if(temp_psg_name.length > 0) {
                                    temp_psg_name.filter(function(x) {
                                        if(x.name.toLowerCase().replace(/[^a-zA-Z ]/g, "") ===  temp_name.toLowerCase().replace(/[^a-zA-Z ]/g, "")){
                                            value.isMatched = true;
                                            resp.data[x.index].isMatched = true;
                                        }else{
                                            value.isMatched = false;
                                        }
                                    })
                                }else{
                                    value.isMatched = false;
                                }

                                temp_psg_name.push({
                                    name: temp_name,
                                    index: index
                                })
                            })
                            //find duplicate name  end


                            $.each(resp.data, function(index, value) {
                                let type = '';
                                let styleDetail = '';

                                if(value.type == 1){
                                    type = 'Adult'
                                }else if(value.type == 2){
                                    type = 'Child'
                                }else{
                                    type = 'Infant'
                                    styleDetail = "color:red;font-weight:800"
                                }
                                if(value.is_refund !== 1){
                                    let temp_name = value.title +' '+value.first_name +' '+ value.last_name;
                                    let temp_name_2 = value.first_name +' '+ value.last_name;
                                    // check exist in the passengers_name array


                                    passengers_name.push({
                                        name: temp_name,
                                        type:type,
                                        temp_name: temp_name_2,
                                        index: index
                                    })

                                }

                                $('#tableBody').append(`
                                <tr class=${(value.is_refund == 1) ? "bg-red " : (value.is_refund == 2) ? "bg-info" : ''} >
                                    <td>${i++}</td>
                                    <td>${value.bill_no}</td>
                                    <td style="${styleDetail}">
                                        ${type}</td>
                                    <td>${value.title} ${value.first_name} ${value.last_name}
                                        <br>
                                        ${value.isMatched ? '<span class="badge bg-danger text-white">Duplicate</span>' : "" }
                                    </td>
                                    <td>${value.agent} <br>  ${value.agent_phone_number}</td>
                                    <td>${value.pax_price}</td>
                                    <td>${value.intimation}</td>
                                    <td>${value.agent_remarks}</td>
                                    <td>${value.internal_remarks}</td>
                                    <td>${value.booking_date}</td>
                                    <td>${value.comments}</td>

                                </tr>
                            `);
                            });

                            $.each(resp.book_detail, function(index, value) {
                                $('#tableBookDetailBody').append(`
                                    <tr>
                                        <td>${value.airline}</td>
                                        <td>${value.pnr}</td>
                                        <td>${value.destination}</td>
                                        <td>${value.travel_date}</td>
                                        <td>${value.flight_no}</td>
                                        <td>${value.departure_time}</td>
                                        <td>${value.arrival_time}</td>
                                        <td>${value.qty}</td>
                                        <td>${value.available}</td>
                                        <td>${value.sold}</td>
                                        <td>${value.trip_type}</td>

                                    </tr>
                                `);
                            });

                            if(resp.book_ticket_details1)
                             {
                                let websitePassengerDetails = resp.book_ticket_details1.passenger_details;
                                let details = [];

                                 $.each(passengers_name, function(index, value) {
                                     details[index] = [];
                                     $.each(websitePassengerDetails, function(index2, value2)
                                     {
                                         if(value2.passenger_name.toLowerCase().replace(/[^a-zA-Z ]/g, "").includes(value.temp_name.toLowerCase().replace(/[^a-zA-Z ]/g, "") )) {
                                             details[index]['pax'] = value.name + "(" + value.type + ")";
                                             details[index]['spicejet_pax'] = value2.passenger_name;
                                             details[index]['gender'] = '';
                                             details[index]['type'] = '';
                                             details[index]['service'] = value2.additional_services_purchased != null  ? value2.additional_services_purchased : '';
                                             value2.matched = true;
                                             return false;
                                         }else {
                                             details[index]['pax'] = value.name + "(" + value.type + ")";
                                             details[index]['spicejet_pax'] = '';
                                             details[index]['gender'] = '';
                                             details[index]['type'] = '';
                                             details[index]['service'] = '';
                                         }
                                     });
                                 });

                            $('#tableBookDetailAirlineBody1').append(`
                                <tr>
                                    <td>${resp.book_ticket_details1.airline}</td>
                                    <td>${resp.book_ticket_details1.pnr}</td>
                                    <td>${resp.book_ticket_details1.source} -  ${resp.book_ticket_details1.destination}</td>
                                    <td>${resp.book_ticket_details1.travel_date}</td>
                                    <td>${resp.book_ticket_details1.flight_no}</td>
                                    <td>${resp.book_ticket_details1.departure_time}</td>
                                    <td>${resp.book_ticket_details1.arrival_time}</td>
                                    <td>${resp.book_ticket_details1.total_pax_count}</td>
                                    <td><span class="badge badge-primary text-uppercase">${resp.book_ticket_details1.pnr_status}</span></td>
                                    <td><span class="badge badge-info  text-uppercase">${resp.book_ticket_details1.flight_status}</span></td>
                                </tr>
                            `);


                            let j = 1;

                            $.each(details, function(index, value) {

                                let type = '';
                                let styleDetail = '';

                                $('#tableBody1').append(`
                                <tr>
                                    <td>${j++}</td>

                                    <td style="${styleDetail}" >${value['pax']}</td>
                                    <td>${value['spicejet_pax']}</td>
                                    <td>${value['gender']}</td>
                                    <td >
                                        ${value['type']}</td>
                                    <td>${(value['service'] != null )?value['service'] : ''} </td>

                                </tr>
                            `);

                            });

                             let notmatchepassengerdetails = [];
                             $.each(websitePassengerDetails,function(index,value) {
                                 console.log(value.matched)
                                 let data = '';
                                 if(value.matched == undefined){
                                     notmatchepassengerdetails.push(value);
                                     // data += `<li>${value.passenger_name}</li>`
                                 }
                             });
                             if(notmatchepassengerdetails.length > 0 )
                             {
                                 let data = '';
                                 $.each(notmatchepassengerdetails,function(index,value) {
                                     data += `<li>${value.passenger_name}</li>`
                                 });

                                 $('#notificationDiv').append(`
                                     <div class="alert alert-warning" role="alert" id="">
                                        <h4>Below name are not match with our records</h4>
                                        <ul>${data} </ul>
                                    </div>`);
                             }




                            }



                        } else {
                            $('#modalAlertMsg').show();
                            $('#modalAlertMsg').html(resp.message);
                        }

                    }
                })
            })


            $('.select2').select2();

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});

            $('.timepicker').datetimepicker({
                format: 'HH:mm',
            });
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

            $('.sellPriceInput').dblclick(function(){
                var data = $(this).attr('data');
                $(this).prop('readonly',false)
            })


            $(document).on('click','.sellPriceBtn.edit', function() {
                var data = $(this).parent().find('.sellPriceInput').attr('data');
                $(this).parent().find('.sellPriceInput').prop('readonly',false);
                $(this).parent().find('.sellPriceInput').focus();
                $(this).removeClass('edit');
                $(this).addClass('save');
                $(this).text('Save');
            });


            $('.sellPriceInput').change(function(e){
                let data = $(this).attr('data');
                let value = $(this).val();
                if(value < 1000) {
                    alert('Can not update with 3 digit figure');
                    return false;
                }
                let resp = confirm("Are you sure want to update the sell price");
                if(resp){
                    $.ajax({
                        url: '/flight-tickets/purchase/sale-price-update',
                        type: 'POST',
                        data:{
                            id: data,
                            amount:value
                        },
                        success: () => {
                            $(this).prop('readonly',true);
                        }
                    })

                }else{
                    $(this).prop('readonly',true);
                }
            })


            function checkQueryParam() {
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('show-ticket')) {
                    let showTicket = urlParams.get('show-ticket');
                    console.log(showTicket);
                    $("#iframeID").attr("src", "/flight-tickets/sales/print/" + showTicket);
                    $("#idFrameAnchor").attr("href", "/flight-tickets/sales/print/" + showTicket);
                    $('#showTicketModal').modal('show');
                }
            }
            $('.btnDelete').click((e) => {
                let resp = confirm("Are you sure you want to delete the Sale Ticket ?")
                if (!resp) {
                    e.preventDefault();
                }
            })
            let destination_order = $('#destination_order').val();
            $('#destinationOrder').click(function(e){
                if(destination_order == ''){
                    destination_order = 'asc'
                }else{
                    destination_order = (destination_order == 'desc') ? 'asc' : 'desc'
                }

                $('#destination_order').val(destination_order);
                $('#available_order').val("");

                $('#searchForm').submit();
            })
            let available_order = $('#available_order').val();

            $('#AvailableQtyOrder').click(function(e){

                if(available_order == ''){
                    available_order = 'asc'
                }else{
                    available_order = (available_order == 'desc') ? 'asc' : 'desc'
                }
                $('#destination_order').val("");
                $('#available_order').val(available_order);
                $('#searchForm').submit();
            })

            $("input[name='checked']").click(function(e) {
                let id = $.trim(e.target.value);
                $('#viewAnchor').attr('value', id);
                $('#bookAnchor').attr('href', '/flight-tickets/bookings/create?book_ticket_id=' + id);
                $('#blockAnchor').attr('href', '/flight-tickets/blocks/create?purchase_id=' + id);
                $('#modifyAnchor').attr('href', '/flight-tickets/purchase/' + id + '/edit');
                $('#statusAnchor').attr('href', '/flight-tickets/purchase/status/' + id);
                $('#ticketPurchaseShowAnchor').attr('href', '/flight-tickets/purchase/' + id);
                $('#NameListShowAnchor').attr('href', '/flight-tickets/pnr-name-list/' + id);
                $('#PnrHistoryShowAnchor').attr('href', '/accounts/pnr-history/show/' + id);
                $('#pnr_fetch').attr('href', "/flight-tickets/pnr-status/" + id);
            });
            $(document).on('dblclick', '.isOnlineButton', function(e) {

                let vm = $(this);
                let value = $(this).val();
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/online-status',
                    type: "POST",
                    data:{
                        status: 1
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-info');
                            vm.removeClass('badge-success');
                            vm.removeClass('isOnlineButton');
                            vm.addClass('isOfflineButton');
                            vm.html('Offline');
                            console.log(value);
                        }

                    }
                })

                //make it offline
            })
            $(document).on('dblclick', '.isOfflineButton', function(e) {

                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/online-status',
                    type: "POST",
                    data:{
                        status: 2
                    },
                    success:function(resp){
                        console.log(resp)
                        if(resp.success){
                            vm.addClass('badge-success');
                            vm.removeClass('badge-info');
                            vm.addClass('isOnlineButton');
                            vm.removeClass('isOfflineButton');
                            vm.html('Online');
                        }


                    }
                })

            })



            $(document).on('dblclick', '.isRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 0
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-grey');
                            vm.removeClass('badge-success');
                            vm.addClass('isNonRefundableButton');
                            vm.removeClass('isRefundableButton');
                            vm.html('Non Refundable');
                        }
                    }
                })

            })

            $(document).on('dblclick', '.isNonRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 1
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-success');
                            vm.removeClass('badge-grey');
                            vm.addClass('isRefundableButton');
                            vm.removeClass('isNonRefundableButton');
                            vm.html('Refundable');
                        }
                    }
                })

            })

            function myFunction(purchase_entry_id,status){

                // $.ajax({
                //     url: '/purchase-entry/'+purchase_entry_id+'/online-status',
                //     type: "POST",
                //     data:{
                //         status: status
                //     },
                //     success:function(resp){
                //         console.log(resp)
                //     })
                // })
            }



        });
    </script>

    <script>
        $(document).on("click", ".tooltip-icon", function() {
            $(this).tooltip(
                {
                    items: ".tooltip-icon",
                    open: function( event, ui ) {
                        var id = this.id;
                        var flight_id = $(this).attr('data-id');

                        $.ajax({
                            url:'/flight-tickets/ajax/last-changed-price-details',
                            type:'POST',
                            data:{
                                flight_id : flight_id
                            },
                            success: function(response){
                                // Setting content option
                                $("#"+id).tooltip('option','content', response);
                            }
                        });
                    },
                    close: function( event, ui ) {
                        var me = this;
                        ui.tooltip.hover(
                            function () {
                                $(this).stop(true).fadeTo(400, 1);
                            },
                            function () {
                                $(this).fadeOut("400", function(){
                                    $(this).remove();
                                });
                            }
                        );
                        ui.tooltip.on("remove", function(){
                            $(me).tooltip("destroy");
                        });
                    },
                }
            );
            $(this).tooltip("open");
        });
        $(document).on("click", ".namelist-info", function() {
            $(this).tooltip(
                {
                    items: ".namelist-info",
                    open: function( event, ui ) {
                        var id = this.id;
                        var flight_id = $(this).attr('data-id');

                        $.ajax({
                            url:'/flight-tickets/ajax/last-changed-namelist-details',
                            type:'POST',
                            data:{
                                flight_id : flight_id
                            },
                            success: function(response){
                                // Setting content option
                                $("#"+id).tooltip('option','content', response);
                            }
                        });
                    },
                    close: function( event, ui ) {
                        var me = this;
                        ui.tooltip.hover(
                            function () {
                                $(this).stop(true).fadeTo(400, 1);
                            },
                            function () {
                                $(this).fadeOut("400", function(){
                                    $(this).remove();
                                });
                            }
                        );
                        ui.tooltip.on("remove", function(){
                            $(me).tooltip("destroy");
                        });
                    },
                }
            );
            $(this).tooltip("open");
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
    <script>
        let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "bookings.csv";
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
                $("#sortable-table-2").first().table2csv('download',{
                    filename: filename,
                    excludeColumns:".slno",
                });
            }
        });
    </script>
@endsection


