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
    </style>
@endsection
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Ticket Purchase Entry</h4>
                <hr>
                <form class="forms-sample row" method="POST" action="{{ route('purchase.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="destination_id" class="col-sm-3 col-form-label">Destination</label>
                            <div class="col-sm-9">
                                <select name="destination_id" id="destination_id" class="form-control select2">
                                    <option value="">Select Destination</option>
                                    @foreach ($destinations as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id == old('destination_id') ) selected @endif>
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
                                        <option value="{{ $value }}" @if ($value==old('airline_id') ) selected @endif>{{ ucwords($key) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pnr" class="col-sm-3 col-form-label">PNR</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pnr" placeholder="Enter the PNR No" name="pnr" value="{{ old('pnr') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="flight_no">Airline Code</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="airline_code" placeholder="Enter the Airline Code" name="airline_code" value="{{ old('airline_code') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="flight_no">Flight No</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" id="flight_no" placeholder="Enter the Flight No" name="flight_no" value="{{ old('flight_no') }}">
                            </div>


                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="travel_date" class="col-form-label">DEPT Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker " id="travel_date" placeholder="Enter the Travel Date" name="travel_date" value="{{ old('travel_date') }}" autocomplete="off" >
                            </div>
                            <div class="col-md-3">
                                <label for="departure_time" class="col-form-label">DEPT Time</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control timepicker" id="departure_time" placeholder="Enter the Departure Time" name="departure_time" value="{{ old('departure_time') }}">
                            </div>

                        </div>



                        <div class="row form-group">
                            {{--<div class="col-md-3">
                                <label for="arrival_time" class=" col-form-label">Arrival Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker" id="arrival_date" placeholder="Enter the Arrival Date" name="arrival_date" value="{{ old('arrival_date') }}">

                            </div>--}}
                            <div class="col-md-3">
                                <label for="arrival_time" class="col-form-label">Arrival Time</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control timepicker" id="arrival_time" placeholder="Enter the Arrival Time" name="arrival_time" value="{{ old('arrival_time') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="infant_charge" class="col-form-label">Infant Charge</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="infant_charge" name="infant_charge" value="{{ old('infant_charge') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantity" placeholder="Enter the Quantity" name="quantity" min="1" value="{{ old('quantity') }}" onchange="if(parseInt(this.value,10) < 10 && this.value.length == 2) this.value=this.value.replace('0', '');">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-3 col-form-label">Base Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="base_price" placeholder="Enter the Base Price" name="base_price" value="{{ old('base_price') }}">
                            </div>
                            <label for="sale_price" class="col-sm-3 col-form-label">Tax</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="tax" placeholder="Enter the TAX" name="tax" value="{{ old('tax') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cost_price" class="col-sm-3 col-form-label">Cost Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="cost_price" placeholder="Enter the Cost Price" name="cost_price" value="{{ old('cost_price') }}">
                            </div>
                            <label for="sale_price" class="col-sm-3 col-form-label">Markup Price</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="markup_price" placeholder="Enter the Markup Price" name="markup_price" value="{{ old('markup_price') }}">

                            </div>
                        </div>
                        <input type="hidden" step="0.01" class="form-control" id="child" placeholder="Enter the Child Price" name="child" value="{{ old('child') }}">
                        <input type="hidden" step="0.01" class="form-control" id="infants" placeholder="Enter the Infants" name="infant" value="{{ old('infant') }}">



                        <div class="form-group row">
                            <label for="owner_id" class="col-sm-3 col-form-label">Vendor</label>
                            <div class="col-sm-9">
                                <select name="owner_id" id="owner_id" class="form-control select2">
                                    <option value="">Select Vendor</option>
                                    @foreach ($owners as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id==old('owner_id')) selected @endif>{{ $value->name }}
                                            @if($value->is_third_party == 1) -  (THIRD PARTY) @endif
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
                                    <option value="Direct" selected>Direct</option>
                                    <option value="1 Stop">1 Stop</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name_list" class="col-sm-3 col-form-label">Name List Day</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="name_list_day" placeholder="Enter the Name List" name="name_list_day" value="{{ old('name_list_day') }}" autocomplete="off">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control datepicker" id="name_list_date" placeholder="Enter the Name List" name="name_list" value="{{ old('name_list') }}" autocomplete="off" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cabin_baggage" class="col-sm-3 col-form-label">Cabin Baggage(KG)</label>
                            <div class="col-sm-3">
                                <input type="number"   class="form-control" id="cabin_baggage" placeholder="Enter the Cabin Baggage" name="cabin_baggage" value="{{ old('cabin_baggage') }}">
                            </div>
                            <label for="checkin_baggage" class="col-sm-3 col-form-label">Check-In Baggage(KG)</label>
                            <div class="col-sm-3">
                                <input type="number"  class="form-control" id="checkin_baggage" placeholder="Enter the CheckIn Baggage" name="checkin_baggage" value="{{ old('checkin_baggage') }}">

                            </div>

                            <label for="cabin_baggage_count" class="col-sm-3 col-form-label">Cabin Baggage(KG)</label>
                            <div class="col-sm-3">
                                <input type="number"  class="form-control" id="cabin_baggage_count" placeholder="Enter the Cabin Baggage Count" name="cabin_baggage_count" value="{{ old('cabin_baggage_count') }}">

                            </div>

                            <label for="checkin_baggage_count" class="col-sm-3 col-form-label">Check-In Baggage Count</label>
                            <div class="col-sm-3">
                                <input type="number"  class="form-control" id="checkin_baggage_count" placeholder="Enter the CheckIn Baggage Count" name="checkin_baggage_count" value="{{ old('checkin_baggage_count') }}">

                            </div>

                            
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary mr-2 btn-sm ">Save</button>
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
            $('.select2').select2();
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
            });


            $('#base_price,#tax,#markup_price').change(function() {
                let base_price = $('#base_price').val();
                let tax = $('#tax').val();
                let markup_price = $('#markup_price').val();
                let total = parseFloat(base_price) + parseFloat(tax);
                $('#cost_price').val(total);
                $('#child').val(parseFloat(total) + parseFloat(markup_price));
            });


            $('#name_list_day,#travel_date').change(function() {
                var travel_date = $("#travel_date").datepicker('getDate');
                var arrival_date = $("#arrival_date").datepicker('getDate');
                $('#arrival_date').datepicker("update", formatDate(travel_date));
                var name_list_day = $("#name_list_day").val();
                if (name_list_day && travel_date) {
                    var name_list_date = addDays(travel_date, name_list_day);
                    $('#name_list_date').datepicker("update", name_list_date);
                    // $('#name_list_date').val(name_list_date);
                }
            })

            function addDays(date, days) {
                var result = date;
                result.setDate(result.getDate() - days);
                return formatDate(result);
            }

            function formatDate(date) {
                var monthNames = [
                    "1", "2", "3",
                    "4", "5", "6", "7",
                    "8", "9", "10",
                    "11", "12"
                ];

                var day = date.getDate();
                console.log(day);
                var monthIndex = date.getMonth();
                var year = date.getFullYear();

                return day + '-' + monthNames[monthIndex] + '-' + year;
            }


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
                            $('#infant_charge').val(result.data.infant_charge);
                            $('#airline_code').val(result.data.code);
                        }
                    })
                }
            });

        });
    </script>

@endsection
