@php
    $baggage_info = $purchase->baggage_info ? json_decode($purchase->baggage_info, true) : [];
@endphp
@extends('layouts.app')
@section('title','Purchase')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Purchase Entry Details of <i>{{ $purchase->pnr }}</i></h4>
                <p>
                    Purchase Entry
                </p>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Destination</th>
                        <td>{{ $purchase->destination->name }} </td>
                    </tr>
                    <tr>
                        <th>Airline</th>
                        <td>{{ $purchase->airline->name }} </td>
                        <th>Base Price</th>
                        <td>{{ $purchase->base_price }}</td>
                        <th>Tax</th>
                        <td>{{ $purchase->tax }}</td>
                    </tr>
                    <tr>
                        <th>PNR</th>
                        <td>{{ $purchase->pnr }} </td>
                        <th>Cost Price</th>
                        <td>{{ $purchase->cost_price }}</td>
                        <th>Sale Price</th>
                        <td>{{ $purchase->sell_price }}</td>
                    </tr>
                    <tr>
                        <th>Flight No</th>
                        <td>{{ $purchase->flight_no }} </td>
                        <th>Owner</th>
                        <td>{{ $purchase->owner->name }}</td>
                        <th>Infant Price</th>
                        <td>{{ $purchase->infant }} </td>
                    </tr>
                    <tr>
                        <th>Travel Date</th>
                        <td>{{ $purchase->travel_date->format('d-M-Y') }}</td>
                        <th>Departure Time</th>
                        <td>{{ $purchase->departure_time }}</td>
                        <th>Flight Route</th>
                        <td>{{ $purchase->flight_route }}</td>
                    </tr>
                    <tr>
                        <th>Arrival Date</th>
                        <td>{{ $purchase->arrival_date->format('d-M-Y') }}</td>
                        <th>Arrival Time</th>
                        <td>{{ $purchase->arrival_time }}</td>
                        <th>Name List</th>
                        <td>{{ $purchase->name_list->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td>{{ $purchase->quantity}}</td>
                        <th>Available</th>
                        <td>{{ $purchase->available}}</td>
                        <th>Sold</th>
                        <td>{{ $purchase->sold}}</td>
                    </tr>
                    <tr>
                        <th>CheckIn Baggage</th>
                        <td>{{ $baggage_info['checkin_baggage'] ?? '' }}</td>
                        <th>Cabin Baggage</th>
                        <td>{{ $baggage_info['cabin_baggage'] ?? '' }}</td>
                          <th>Block</th>
                        <td>{{ $purchase->blocks}}</td>
                    </tr>
                </table>
                <hr>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Change History</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Status History</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab1" data-toggle="tab" href="#profile1" role="tab" aria-controls="profile" aria-selected="false">Price History</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab1" data-toggle="tab" href="#profile2" role="tab" aria-controls="profile" aria-selected="false">Sales</a>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>IP</th>
                                    <th>Created At</th>
                                    <th>Old Data</th>
                                    <th>New Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->audits as $key => $val)
                                    <tr>
                                        <td>{{ 1+ $key }}</td>
                                        <td>{{ $val->user->first_name }} {{ $val->user->last_name }}</td>
                                        <td>{{ $val->ip_address }}</td>
                                        <td>{{ $val->created_at->format('d-M-Y H:i:s') }}</td>
                                        <td>
                                            @foreach($val->old_values as $key => $value)
                                                @if($key == 'isOnline')
                                                    @if($value == 1)
                                                        {{ $key}} :offline <br>
                                            @elseif($value == 2)
                                                    {{ $key}} : online <br>
                                                @endif
                                                @elseif($key == 'namelist_status')
                                                @if($value == 1)
                                                    {{ $key }}: Partially Send  <br>
                                                @elseif($value == 2)
                                                    {{ $key }}: Fully Send <br>
                                                @elseif($value == 3)
                                                    {{ $key }}: Checked <br>
                                                @elseif($value == 4)
                                                    {{ $key }}: Partially DB Check <br>
                                                @elseif($value == 5)
                                                    {{ $key }}: Fully DB Check <br>
                                                @endif

                                                @else
                                                    {{ $key}} : {{ $value }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                        @foreach($val->new_values as $key2 => $value2)
                                                @if($key2 == 'isOnline')
                                                    @if($value2 == 1)
                                                        {{ $key2}} :offline <br>
                                            @elseif($value2 == 2)
                                                    {{ $key2}} : online <br>
                                                @endif

                                                @elseif($key2 == 'namelist_status')
                                                    @if($value2 == 1)
                                                        {{ $key2 }}: Partially Send  <br>
                                                    @elseif($value2 == 2)
                                                        {{ $key2 }}: Fully Send <br>
                                                    @elseif($value2 == 3)
                                                        {{ $key2 }}: Checked <br>
                                                    @elseif($value2 == 4)
                                                        {{ $key2 }}: Partially DB Check <br>
                                                    @elseif($value2 == 5)
                                                        {{ $key2 }}: Fully DB Check <br>
                                                    @endif
                                                @elseif($key2 == 'baggage_info')
                                                    {{ $key2}} : {{ json_encode($value2) }}
                                                @else
                                                    {{ $key2}} : {{ $value2 }}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>User</th>
                                    <th>Remarks</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($purchase->status as $i => $val)
                                    <tr>
                                        <td>{{ 1 + $i }}</td>
                                        <td>

                                            @if($val->type == 1)
                                                <span class="badge badge-warning">IROP</span>
                                            @elseif($val->type == 2)
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-success">On time</span>
                                            @endif


                                        </td>
                                        <td>
                                            @if($val->type == 1  )

                                                @foreach($val->data as $j => $val1)
                                                    {{$j }}{{ $val1}} <br>
                                                @endforeach


                                            @endif
                                        </td>
                                        <td>{{ $val->user->first_name }} {{ $val->user->last_name }}</td>
                                        <td>{{ $val->remarks }}</td>
                                        <td>
                                            {{ $val->created_at->format('d-m-y h:i:s')}}
                                        </td>

                                    </tr>


                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile1" role="tabpanel" aria-labelledby="profile-tab1">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>New Price</th>
                                    <th>User</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->price_log as $i => $val)
                                    <tr>
                                        <td>{{ 1 + $i }}</td>

                                        <td>
                                            {{ $val->new_price}}
                                        </td>
                                        <td>{{ $val->owner->first_name}} {{ $val->owner->last_name}}</td>
                                        <td>
                                            {{ $val->created_at->format('d-m-y h:i:s')}}
                                        </td>

                                    </tr>


                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile2" role="tabpanel" aria-labelledby="profile-tab1">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Agent</th>
                                        <th>Bill No</th>
                                        <th>Pax</th>
                                        <th>Price</th>
                                        <th>Infant</th>
                                        <th>Infant Price</th>
                                        <th>Booking Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($purchase->baggage_info)
                    <h4 class="card-title mt-4">Baggage Info</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Cabin Baggage (KG)</th>
                                    <th>Check-In Baggage (KG)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $baggage_info = json_decode($purchase->baggage_info, true);
                                @endphp
                                <tr>
                                    <td>{{ $baggage_info['cabin_baggage'] ?? 'N/A' }}</td>
                                    <td>{{ $baggage_info['checkin_baggage'] ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
