@extends('layouts.app')
@section('title','Fare management')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" />
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
                <h4 class="card-title text-uppercase">Edit Ticket Purchase Entry</h4>
                <p class="card-description">
                    Edit Ticket Purchase Entry to the application
                </p>

                <form class="forms-sample row" method="POST" action="{{ route('purchase-entry.update', $data->id) }}">
                    @csrf
                    @method('put')
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="destination_id" class="col-sm-3 col-form-label">Destination</label>
                            <div class="col-sm-9">
                                <select name="destination_id" id="destination_id" class="form-control select2">
                                    <option value="">Select Destination</option>
                                    @foreach ($destinations as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id == request()->query('destination_id')) selected @endif>
                                            {{ ucwords($value->name) }}
                                            {{ $value->code }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="airline_id" class="col-sm-3 col-form-label">Airline</label>
                            <div class="col-sm-9">
                                <select name="airline_id" id="airline_id" class="form-control select2">
                                    <option value="">Select Airline</option>
                                    @foreach ($airlines as $key => $value)
                                        <option value="{{ $value }}" @if ($value == $data->airline_id) selected @endif>{{ ucwords($key) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pnr" class="col-sm-3 col-form-label">PNR</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pnr" placeholder="Enter the PNR No" name="pnr"
                                    value="{{ $data->pnr }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="flight_no" class="col-sm-3 col-form-label">Flight No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="flight_no" placeholder="Enter the Flight No"
                                    name="flight_no" value="{{ $data->flight_no }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="travel_date" class="col-sm-3 col-form-label">Travel Date</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datepicker " id="travel_date"
                                    placeholder="Enter the Travel Date" name="travel_date"
                                    value="{{ $data->travel_date->format('m/d/Y') }}" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name_list" class="col-sm-3 col-form-label">Name List</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datepicker" id="name_list"
                                    placeholder="Enter the Name List" name="name_list"
                                    value="{{ $data->name_list->format('m/d/Y') }}" autocomplete="off" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2 btn-sm">Update</button>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="departure_time" class="col-sm-3 col-form-label">Departure Time</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control timepicker" id="departure_time"
                                    placeholder="Enter the Departure Time" name="departure_time"
                                    value="{{ $data->departure_time }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="arrival_time" class="col-sm-3 col-form-label">Arrival Time</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control timepicker" id="arrival_time"
                                    placeholder="Enter the Arrival Time" name="arrival_time"
                                    value="{{ $data->arrival_time }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantity" placeholder="Enter the Quantity"
                                    name="quantity" value="{{ $data->quantity }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-3 col-form-label">Cost Price</label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" class="form-control" id="cost_price"
                                    placeholder="Enter the Cost Price" name="cost_price" value="{{ $data->cost_price }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sale_price" class="col-sm-3 col-form-label">Sales Price</label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" class="form-control" id="sale_price"
                                    placeholder="Enter the Sale Price" name="sale_price" value="{{ $data->sell_price }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="owner_id" class="col-sm-3 col-form-label">Owner</label>
                            <div class="col-sm-9">
                                <select name="owner_id" id="owner_id" class="form-control select2">
                                    <option value="">Select Owner</option>
                                    @foreach ($owners as $key => $value)
                                        <option value="{{ $value }}" @if ($value == $data->owner_id) selected @endif>{{ $key }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="flight_route" class="col-sm-3 col-form-label">Flight Route</label>
                            <div class="col-sm-9">
                                <select name="flight_route" id="flight_route" class="form-control select2">
                                    <option value="">Select Flight Route</option>
                                    <option value="Direct" @if ('Direct' == $data->flight_route) selected @endif>Direct</option>
                                    <option value="1 Stop" @if ('1 Stop' == $data->flight_route) selected @endif>1 Stop</option>
                                </select>
                            </div>
                        </div>
                    </div>




                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
            });



        });
    </script>

@endsection
