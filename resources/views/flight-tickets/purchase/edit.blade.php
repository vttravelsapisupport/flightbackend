@extends('layouts.app')
@section('title','Purchase')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }

        .select2{
            width: 100% !important;
        }
    </style>

@endsection
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Edit Ticket Purchase Entry</h4>
                <hr>

                <form class="forms-sample row" method="POST" action="{{ route('purchase.update', $data->id) }}">
                    @csrf
                    @method('put')
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="destination_id" class="col-sm-3 col-form-label">Destination</label>
                            <div class="col-sm-9">
                                <select name="destination_id" id="destination_id" class="form-control select2">
                                    <option value="">Select Destination</option>
                                    @foreach ($destinations as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id == $data->destination_id) selected @endif>
                                            {{ ucwords($value->name) }} {{ $value->code }}
                                        </option>

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
                                        <option value="{{ $value }}" @if ($value==$data->airline_id) selected @endif>{{ ucwords($key) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pnr" class="col-sm-3 col-form-label">PNR</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pnr" placeholder="Enter the PNR No" name="pnr" value="{{ $data->pnr }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="flight_no">Airline Code</label>
                            </div>
                            @php
                                $array_flight_no = explode(" ",$data->flight_no);
                                $airline_code = $array_flight_no[0];
                                $flight_no = $array_flight_no[1];

                            @endphp
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="airline_code" placeholder="Enter the Airline Code" name="airline_code" value="{{$airline_code}}">
                            </div>
                            <div class="col-md-3">
                                <label for="flight_no">Flight No</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" id="flight_no" placeholder="Enter the Flight No" name="flight_no" value="{{ $flight_no }}">
                            </div>


                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="travel_date" class="col-form-label">Travel Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker " id="travel_date" placeholder="Enter the Travel Date" name="travel_date" value="{{ $data->travel_date->format('d-m-Y') }}" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label for="departure_time" class="col-form-label">Departure Time</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control timepicker" id="departure_time" placeholder="Enter the Departure Time" name="departure_time" value="{{ $data->departure_time }}">
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="arrival_time" class=" col-form-label">Arrival Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker" id="arrival_date" placeholder="Enter the Arrival Date" name="arrival_date" value="@if($data->arrival_date){{ $data->arrival_date->format('d-m-Y') }} @else @endif">

                            </div>
                            <div class="col-md-3">
                                <label for="arrival_time" class="col-form-label">Arrival Time</label>

                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control timepicker" id="arrival_time" placeholder="Enter the Arrival Time" name="arrival_time" value="{{ $data->arrival_time }}">
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="name_list" class="col-sm-3 col-form-label">Name List</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control datepicker" id="name_list" placeholder="Enter the Name List" name="name_list" value="{{ $data->name_list->format('d-m-Y') }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @can('purchase_qty_update')
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantity" placeholder="Enter the Quantity" name="quantity" value="{{ $data->quantity }}">
                            </div>
                        </div>
                        @endcan
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-3 col-form-label">Base Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="base_price" placeholder="Enter the Base Price" name="base_price" value="{{ $data->base_price }}">
                            </div>
                            <label for="sale_price" class="col-sm-3 col-form-label">Tax</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="tax" placeholder="Enter the TAX" name="tax" value="{{ $data->tax }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-3 col-form-label">Cost Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="cost_price" placeholder="Enter the Cost Price" name="cost_price" value="{{ $data->cost_price }}">
                            </div>
                            <label for="sale_price" class="col-sm-3 col-form-label">Sale Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="sale_price" placeholder="Enter the Sale Price" name="sell_price" value="{{ $data->sell_price }}">
                            </div>
                        </div>
                        <input type="hidden" step="0.01" class="form-control" id="child" placeholder="Enter the Child Price" name="child" value="{{$data->child }}">
                        <div class="form-group row">
                            <label for="infants" class="col-sm-3 col-form-label">Infant Price</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="infants" placeholder="Enter the Infants" name="infant" value="{{ $data->infant }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="owner_id" class="col-sm-3 col-form-label">Owner</label>
                            <div class="col-sm-9">
                                <select name="owner_id" id="owner_id" class="form-control select2">
                                    <option value="">Select Owner</option>
                                    @foreach ($owners as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id==$data->owner_id) selected @endif>{{ $value->name }}  @if($value->is_third_party == 1) -  (THIRD PARTY) @endif
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
                                    <option value="Direct" @if ('Direct'==$data->flight_route) selected @endif>Direct</option>
                                    <option value="1 Stop" @if ('1 Stop'==$data->flight_route) selected @endif>1 Stop</option>
                                </select>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                        <label for="cabin_baggage" class="col-sm-3 col-form-label">Cabin Baggage (KG)</label>
                        <div class="col-sm-3">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="cabin_baggage" 
                                name="cabin_baggage" 
                                placeholder="Enter the Cabin Baggage"
                                value="{{ old('cabin_baggage', $baggage_info['cabin_baggage'] ?? null) }}"
                            >
                        </div>

                        <label for="checkin_baggage" class="col-sm-3 col-form-label">Check-In Baggage (KG)</label>
                        <div class="col-sm-3">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="checkin_baggage" 
                                name="checkin_baggage" 
                                placeholder="Enter the Check-In Baggage"
                                value="{{ old('checkin_baggage', $baggage_info['checkin_baggage'] ?? null) }}"
                            >
                        </div>
                        <label for="cabin_baggage_count" class="col-sm-3 col-form-label">Cabin Baggage Count</label>
                        <div class="col-sm-3">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="cabin_baggage_count" 
                                name="cabin_baggage_count" 
                                placeholder="Enter the Cabin Baggage"
                                value="{{ old('cabin_baggage_count', $baggage_info['cabin_baggage_count'] ?? null) }}"
                            >
                        </div>

                        <label for="checkin_baggage_count" class="col-sm-3 col-form-label">Check-In Baggage Count</label>
                        <div class="col-sm-3">
                            <input 
                                type="number" 
                                class="form-control" 
                                id="checkin_baggage_count" 
                                name="checkin_baggage_count" 
                                placeholder="Enter the Check-In Baggage"
                                value="{{ old('checkin_baggage_count', $baggage_info['checkin_baggage_count'] ?? null) }}"
                            >
                        </div>
                    </div>

                    </div>
                    <div id="one-stop" style="width:100%">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label for="source_id" class="col-form-label">Source</label><br>
                                    <select name="source_id_1" id="source_id_1" class="form-control select2 one-stop">
                                        <option value="">Select Source</option>
                                        @foreach ($airports as $key => $value)
                                            <option value="{{$value->code}}"
                                            @if($segments && $segments[0]->origin == $value->code)
                                            selected
                                            @endif
                                            >
                                                {{ ucwords($value->name) }} {{ $value->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="destination_id" class="col-form-label">Destination</label><br>
                                    <select name="destination_id_1" id="destination_id_1" class="form-control select2 one-stop">
                                        <option value="">Select Destination</option>
                                        @foreach ($airports as $key => $value)
                                            <option value="{{$value->code}}" @if($segments && $segments[0]->destination == $value->code) selected @endif>
                                                {{ ucwords($value->name) }} {{ $value->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="flight_no" class="col-form-label">Flight No</label>
                                    <input type="number" class="one-stop form-control" id="flight_no_1" placeholder="Flight No" name="flight_no_1" value="{{ isset($segments) ? $segments[0]->flight_number : null }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="travel_date_1" class="col-form-label">DEPT Date</label>
                                    <input type="text" class="one-stop form-control datepicker " id="travel_date_1" placeholder="Travel Date" name="travel_date_1" value="{{ isset($segments) ? date('d-m-Y', strtotime($segments[0]->departure_date)) : null }}" autocomplete="off" >
                                </div>
                                <div class="col-md-2">
                                    <label for="departure_time_1" class="col-form-label">DEPT Time</label>
                                    <input type="text" class="one-stop form-control timepicker" id="departure_time_1" placeholder="Departure Time" name="departure_time_1" value="{{ isset($segments) ? $segments[0]->departure_time : null}}">
                                </div>
                                <div class="col-md-2">
                                    <label for="arrival_time_1" class="col-form-label">Arrival Time</label>
                                    <input type="text" class="one-stop form-control timepicker" id="arrival_time_1" placeholder="Arrival Time" name="arrival_time_1" value="{{ isset($segments) ? $segments[0]->arrival_time : null }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row mt-3 one-stop">
                                <div class="col-md-2">
                                    <label for="source_id_2" class="col-form-label">Source</label><br>
                                    <select  name="source_id_2" id="source_id_2" class="one-stop form-control select2">
                                        <option value="">Select Source</option>
                                        @foreach ($airports as $key => $value)
                                            <option value="{{$value->code}}"
                                            @if(isset($segments[1]) && $segments[1]->origin == $value->code)
                                            selected
                                            @endif
                                            >
                                                {{ ucwords($value->name) }} {{ $value->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="destination_id_2" class="col-form-label">Destination</label><br>
                                    <select  name="destination_id_2" id="destination_id_2" class="one-stop form-control select2">
                                        <option value="">Select Destination</option>
                                        @foreach ($airports as $key => $value)
                                            <option value="{{$value->code}}"
                                            @if(isset($segments[1]) && $segments[1]->destination == $value->code)
                                            selected
                                            @endif
                                            >
                                                {{ ucwords($value->name) }} {{ $value->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="flight_no_2" class="col-form-label">Flight No</label>
                                    <input type="number" class="one-stop form-control" id="flight_no_2" placeholder="Flight No" name="flight_no_2" value="{{ isset($segments[1]) ? $segments[1]->flight_number : null }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="travel_date_2" class="col-form-label">DEPT Date</label>
                                    <input type="text" class="one-stop form-control datepicker " id="travel_date_2" placeholder="Travel Date" name="travel_date_2" value="{{ isset($segments[1]) ? date('d-m-Y', strtotime($segments[1]->departure_date)) : null }}" autocomplete="off" >
                                </div>
                                <div class="col-md-2">
                                    <label for="departure_time_2" class="col-form-label">DEPT Time</label>
                                    <input type="text" class="one-stop form-control timepicker" id="departure_time_2" placeholder="Departure Time" name="departure_time_2" value="{{ isset($segments[1]) ? $segments[1]->departure_time : null }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="arrival_time_2" class="col-form-label">Arrival Time</label>
                                    <input type="text" class="one-stop form-control timepicker" id="arrival_time_2" placeholder="Arrival Time" name="arrival_time_2" value="{{ isset($segments[1]) ? $segments[1]->arrival_time : null }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="text-left">
                                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Update</button>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var type = '<?php echo $data->flight_route?>';
            if(type == 'Direct') {
                $("#one-stop").hide();
            }else{
                $(".one-stop").attr('required', 'required');
            }

            $("#flight_route").on('change', function() {
                var type = $(this).val();
                if(type == 'Direct') {
                    $("#one-stop").hide();
                    $(".one-stop").removeAttr('required', 'required');
                }else{
                    $("#one-stop").show();
                    $(".one-stop").attr('required', 'required');
                }
            });

            $('.select2').select2();

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
            });
            $('#base_price,#tax,#sale_price').change(function() {
                let base_price = $('#base_price').val();
                let tax = $('#tax').val();
                let sale_price = $('#sale_price').val()
                let total = parseFloat(base_price) + parseFloat(tax);
                $('#cost_price').val(total);
                $('#child').val(parseFloat(sale_price));
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#airline_id").change(function() {
                var id = $(this).val();
                if(id){
                    $.ajax({
                        url: '/flight-tickets/ajax/airline-details',
                        type: 'GET',
                        data:{
                            id: id
                        },
                        success: (result) => {
                            $('#infants').val(result.data.infant_charge);
                        }
                    })
                }
            });
        });
    </script>

@endsection
