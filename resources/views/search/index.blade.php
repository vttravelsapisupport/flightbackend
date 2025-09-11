@extends('layouts.app')
@section('title','Bookings')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
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
        /* .customcss {
            background-color: #e4e4e4e4;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 0px 10px #e2e2e2;
        } */
    </style>
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                   <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Flight Ticket Search</h4>
                        <p class="card-description">Search all the flight availables</p>
                    </div>

                </div>
                <br>
                <form class="forms-sample row " method="GET" action="" id="searchForm">

                    <div class="col-md-2 mb-2">
                        <select name="origin" id="origin" required class="form-control form-control-sm origin select2" style="width:100%">
                            <option value="">Select Origin</option>
                            @if($origin_details)
                                <option value="{{ $origin_details['code'] }}" selected> {{ $origin_details['name']}}</option>
                            @endif

                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="destination" id="destination" required class="form-control form-control-sm destination select2" style="width:100%">
                            <option value="">Select Destination</option>
                            @if($destination_details)
                                 <option value="{{ $destination_details['code'] }}" selected>{{ $destination_details['name']}}</option>
                             @endif
                        </select>
                    </div>

                    <div class="col-md-2  mb-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker"
                               name="departure_date" id="travel_date_from" autocomplete="off" required
                               placeholder="Select Departure Date" value="{{ request()->query('departure_date') }}">
                    </div>
                    <div class="col-md-6">

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1" >Adults</span>
                            <select name="adult" id="adult" required class="form-control form-control-sm select2">
                                <option value="1" @if(request()->query('adult') == 1) selected @endif>1</option>
                                <option value="2" @if(request()->query('adult') == 2) selected @endif>2</option>
                                <option value="3" @if(request()->query('adult') == 3) selected @endif>3</option>
                                <option value="4" @if(request()->query('adult') == 4) selected @endif>4</option>
                                <option value="5" @if(request()->query('adult') == 5) selected @endif>5</option>
                                <option value="6" @if(request()->query('adult') == 6) selected @endif>6</option>
                                <option value="7" @if(request()->query('adult') == 7) selected @endif>7</option>
                                <option value="8" @if(request()->query('adult') == 8) selected @endif>8</option>
                                <option value="9" @if(request()->query('adult') == 9) selected @endif>9</option>
                            </select>
                            <span class="input-group-text" id="basic-addon1">Child</span>
                            <select name="child" id="child"  class="form-control form-control-sm select2 ">
                                <option value="0"  @if(request()->query('child') == 0) selected @endif>0</option>
                                <option value="1"  @if(request()->query('child') == 1) selected @endif>1</option>
                                <option value="2"  @if(request()->query('child') == 2) selected @endif>2</option>
                                <option value="3"  @if(request()->query('child') == 3) selected @endif>3</option>
                                <option value="4"  @if(request()->query('child') == 4) selected @endif>4</option>
                                <option value="5"  @if(request()->query('child') == 5) selected @endif>5</option>
                                <option value="6"  @if(request()->query('child') == 6) selected @endif>6</option>
                                <option value="7"  @if(request()->query('child') == 7) selected @endif>7</option>
                                <option value="8"  @if(request()->query('child') == 8) selected @endif>8</option>
                                <option value="9"  @if(request()->query('child') == 9) selected @endif>9</option>
                            </select>
                            <span class="input-group-text" id="basic-addon1">Infants</span>
                            <select name="infant" id="infant" class="form-control form-control-sm select2">
                                <option value="0" @if(request()->query('infant') == 0) selected @endif>0</option>
                                <option value="1" @if(request()->query('infant') == 1) selected @endif>1</option>
                                <option value="2" @if(request()->query('infant') == 2) selected @endif>2</option>
                            </select>
                            <div class="input-group-prepend">
                                <button class="btn btn-behance btn-block btn-sm"  name="search_btn" value="search">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="row mt-2">
                        <div class="col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-sm ">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="2%" class="slno">Sl no.</th>

                                            <th class="sortStyle">Airline<i class="mdi mdi-chevron-down"></i></th>
                                            <th class="sortStyle">Flight No <i class="mdi mdi-chevron-down"></i></th>
                                            <th id="destinationOrder" >Sectors</th>

                                            <th class="sortStyle">Trip Type</th>
                                            <th  id="AvailableQtyOrder" width="2%">Seat Avlb</th>


                                            <th class="sortStyle">Adult Price</th>
                                            <th class="sortStyle">Child Price</th>
                                            <th class="sortStyle" >Inf Price</th>
                                            <th class="sortStyle">Total Price</th>
                                            <th class="sortStyle">Travel Date & time<i class="mdi mdi-chevron-down"></i></th>
                                            <th class="sortStyle">Arrival Date & time<i class="mdi mdi-chevron-down"></i></th>
                                            <th>Type</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($results as $k => $v)
                                        <tr>
                                            <td>{{ 1 +  $k }}</td>
                                            <td>{{ $v->airline_code }}</td>
                                            <td>{{ $v->flight_no }}</td>
                                            <td>{{ $v->origin_code }}{{ $v->destination_code }}</td>
                                            <td>
                                                @php
                                                    $flight_details = json_encode($v->segments);
                                                @endphp
                                                <a  class="flight_details"
                                                    href="javascript:void(0)"
                                                    data-value="{{ $flight_details }}"
                                                >Flight Details</a>
                                            </td>
                                            <td>{{ $v->seats_available }}</td>
                                            <td>{{ $v->adult }}</td>
                                            <td>
                                                @if(isset($v->child))
                                                    {{ $v->child }}
                                                @endif
                                            </td>
                                            <td>@if(isset($v->infant))
                                                    {{ $v->infant }}
                                                @endif</td>
                                            <td>{{ $v->total }}</td>
                                            <td>{{ Carbon\Carbon::parse($v->departure_date)->format('d-m-Y') }} {{ $v->departure_time }}</td>
                                            <td>{{ Carbon\Carbon::parse($v->arrival_date)->format('d-m-Y') }} {{ $v->arrival_time }}</td>
                                            <td>@if($v->non_refundable == 1)
                                                    <label for="" class="badge badge-warning">Non Refundable</label>
                                                @else
                                                    <label for="" class="badge badge-success">Refundable</label>

                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                            </table>

                            <div class="mt-3">

                            </div>
                        </div>
                    </div>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="width: auto;">
                            <div class="modal-header" style="padding: 10px;">
                                <h5 class="modal-title" id="exampleModalLongTitle">Flight Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1rem 2rem;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="padding: 10px;">
                                <table id="sortable-table-2" class="table table-bordered table-sm sortable-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="sortStyle">Sl no.</th>
                                        <th class="sortStyle">Flight No </th>
                                        <th class="sortStyle">Origin</th>
                                        <th class="sortStyle">Destination</th>
                                        <th class="sortStyle">Duration</th>
                                        <th class="sortStyle">DPT </th>
                                        <th class="sortStyle">ARV </th>

                                        <th class="sortStyle">Type</th>
                                        <th class="sortStyle">Legs</th>
                                    </tr>
                                    </thead>
                                    <tbody id="flight_details_table">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer" style="padding: 2px;">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{  asset('assets/js/jq.tablesort.js') }}"></script>
    <script>
        $(document).ready(function() {

            let urlParams = new URLSearchParams(window.location.search);
            let originParam = urlParams.get('origin');
            let destinationParam = urlParams.get('destination');
            let adultParam = urlParams.get('adult');
            let childParam = urlParams.get('child');
            let infantParam = urlParams.get('infant');



        let bgcolortr = ['table-success','table-warning'];

        $('.flight_details').click( function(){
            $('#flight_details_table').empty();
            let data = $(this).attr('data-value');
            data = JSON.parse(data)
            console.log(data);
            data.forEach((e,i) => {
                let legs = e.legs.length;
                let segment_count = i + 1 ;
                e.legs.forEach((l,j) => {
                $('#flight_details_table').append(`
                    <tr class="text-center ${bgcolortr[i]} ">
                        <td>${ i + 1}</td>
                        <td>${l.airline_code} ${l.flight_number}</td>
                        <td>${l.origin} </td>
                        <td>${l.destination} </td>
                        <td>${l.duration}</td>
                        <td>${l.departure_date} ${l.departure_time}</td>
                        <td>${l.arrival_date} ${l.arrival_time}</td>
                        <td><span>${ (segment_count == 1) ? 'outbound' : 'inbound' }</span>
                            <!-- <span>Outboud</span> -->
                        </td>
                        <td>${j+1}</td>
                    </tr>`)
                })
            })
            $('#exampleModalCenter').modal('show');
        })
        $(".datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $("#origin, #destination").select2({
            placeholder: "Select a Airport",
            width: '100%',
            allowClear: true,
            ajax: {
                url: '/ajax/search/airports',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },

                dataType: 'json',
                cache: true
            },
            minimumInputLength: 3,
        });
        $("form").submit(function (e) {
            let originValue = $("#origin").val();
            let destinationValue = $("#destination").val();
            if (originValue === destinationValue) {
                alert("Origin and Destinations cannot be the same");

                $("#origin").val(null).trigger('change');
                $("destination").val(null).trigger('change');

                e.preventDefault();

            }
        })
    })
</script>
@endsection


