@extends('layouts.app')
@section('title','Purchase')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
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
                <h4 class="card-title text-uppercase">Change status of the Purchase Entry {{ $data->pnr }}</h4>

                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Destination</th>
                        <td>{{ $data->destination->name }} </td>

                    </tr>
                    <tr>
                        <th>Airline</th>
                        <td>{{ $data->airline->name }} </td>
                        <th>Base Price</th>
                        <td>{{ $data->base_price }}</td>
                        <th>Tax</th>
                        <td>{{ $data->tax }}</td>
                    </tr>
                    <tr>
                        <th>PNR</th>
                        <td>{{ $data->pnr }} </td>
                        <th>Cost Price</th>
                        <td>{{ $data->cost_price }}</td>
                        <th>Sale Price</th>
                        <td>{{ $data->sell_price }}</td>
                    </tr>
                    <tr>
                        <th>Flight No</th>
                        <td>{{ $data->flight_no }} </td>
                        <th>Owner</th>
                        <td>{{ $data->owner->name }}</td>
                    </tr>
                    <tr>
                        <th>Travel Date</th>
                        <td>{{ $data->travel_date->format('d-M-Y') }}</td>
                        <th>Departure Time</th>
                        <td>{{ $data->departure_time }}</td>
                        <th>Flight Route</th>
                        <td>{{ $data->flight_route }}</td>
                    </tr>
                    <tr>
                        <th>Arrival Date</th>
                        <td>{{ $data->arrival_date->format('d-M-Y') }}</td>
                        <th>Arrival Time</th>
                        <td>{{ $data->arrival_time }}</td>
                        <th>Name List</th>
                        <td>{{ $data->name_list->format('d-M-Y') }}</td>

                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td>{{ $data->quantity}}</td>
                        <th>Available</th>
                        <td>{{ $data->available}}</td>
                        <th>Sold</th>
                        <td>{{ $data->sold}}</td>
                        <th>Block</th>
                        <td>{{ $data->blocks}}</td>
                    </tr>
                </table>
                <hr>
                <form class="forms-sample" method="POST" action="{{ url('/flight-tickets/purchase/status-update') }}">
                    @csrf
                    <input type="hidden" name="purchase_entry_id" value="{{ $data->id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Type</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="form-check form-check-inline">
                                            <input  type="radio" id="inlineRadio1" value="irop" name="type" checked>
                                            <label>IROP</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="inlineRadio2"  name="type"  value="cancel" >
                                            <label >Cancelled</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="inlineRadio2"  name="type"  value="ontime" >
                                            <label >Ontime</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Travel Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datepicker"  name="travel_date" placeholder="Enter the Travel Date" value="{{$data->travel_date->format('d-m-Y')}}">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Namelist Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datepicker"  name="name_list" placeholder="Enter the Name Date" value="{{$data->name_list->format('d-m-Y')}}">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Arrival Time</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control timepicker"  name="arrival_time"  placeholder="Enter the Travel Date" value="{{$data->arrival_time }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea  id="" cols="30" rows="10" class="form-control" name="remarks" required ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Travel Time</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control timepicker" name="departure_time" placeholder="Enter the Travel Time" value="{{$data->departure_time }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Arrival Date</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datepicker" name="arrival_date" placeholder="Enter the Travel Date" value="{{$data->arrival_date->format('d-m-Y') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="flight_no">Airline</label>
                                </div>
                            <div class="col-md-3">
                                @php
                                    $array_flight_no = explode(" ",$data->flight_no);
                                    $airline_code = $array_flight_no[0];
                                    $flight_no = $array_flight_no[1];
                                @endphp
                                    <select name="airline_code" id="airline_code" class="form-control">
                                        @foreach($airlines as $k => $v)
                                        <option value="{{ $v->code}}" @if($v->code == $airline_code) selected @endif> {{ $v->name }} ({{ $v->code }})</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label for="flight_no">Flight No</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="flight_no" placeholder="Enter the Flight No" name="flight_no" value="{{ $flight_no }}">
                                </div>


                            </div>

                            <div class="form-group row">
                                <label for="flight_route" class="col-sm-3 col-form-label">Flight Route</label>
                                <div class="col-sm-9">
                                    <select name="flight_route" id="flight_route" class="form-control select2">
                                        <option value="">Select Flight Route</option>
                                        <option value="Direct" @if ('Direct'==$data->flight_route) selected @endif>Direct</option>
                                        <option value="1 Stop" @if ('1 Stop'==$data->flight_route) selected @endif>1 Stop</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Update</button>

                    </div>

            </div>




            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('input[type=radio][name=type]').change(function() {
            console.log(this.value);
        });
        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $('.timepicker').datetimepicker({
            format: 'HH:mm',
        });
    </script>


@endsection
