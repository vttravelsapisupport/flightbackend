@extends('layouts.app')
@section('title','Bookings')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="offset-1 col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Book Ticket</h4>
                <p class="card-description">
                    Book Ticket
                </p>
                <hr>

                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $data->id }}">
                    <h6 class="text-uppercase">Agent Details</h6>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Select Source</label>
                                <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2" required>
                                    <option value=""> Select Source</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Email Id </label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="email" id="email">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Mobile No</label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="mobile" id="mobile">
                            </div>

                        </div>
                    </div>
                    <h6 class="text-uppercase">Flight Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Airline and PNR No</label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="pnr_no" value="{{ $data->airline->name }} ({{ $data->flight_no }}) - {{ $data->pnr}}">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Destination</label>

                                <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->destination->name }}">


                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Travel Date and Time</label>
                                <input type="hidden" id="travel_date_hidden" value="{{ $data->travel_date->format('Y-m-d') }} ">
                                <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->travel_date->format('d-m-Y') }} - {{ $data->departure_time}}">

                            </div>
                        </div>
                    </div>
                    <h5 class="text-uppercase row">
                        <div class="col-md-6">
                            Passenger Details
                        </div>

                    </h5>
                    <h6 class="text-uppercase row">
                        <div class="col-md-6">
                            Adults
                        </div>
                        <div class="col-md-6 text-right">
                        Fill with TBA <input type="checkbox" id="fillWithTBACheckBox">
                            <button class="btn btn-sm btn-behance addButton" type="button">Add Adults</button>
                        </div>
                    </h6>
                    <table class="table  table-bordered table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="PassengerListBody">
                        <tr>
                            <td>1</td>
                            <td>
                                <select name="title[]" id="agent_id" class="form-control form-control-sm" required>

                                    <option value="Mr" selected> Mr</option>
                                    <option value="Mrs"> Mrs</option>
                                    <option value="Ms"> Ms</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" name="first_name[]" autocomplete="off" required></td>
                            <td><input type="text" class="form-control form-control-sm" name="last_name[]" autocomplete="off" required></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-red deleteBtn" type="button">
                                        <i class="mdi mdi-trash-can"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6 class="text-uppercase row">
                        <div class="col-md-6">
                            Child
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-sm btn-behance addChildButton" type="button">Add Child</button>
                        </div>
                    </h6>
                    <table class="table  table-bordered table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="ChildListBody">

                        </tbody>
                    </table>
                    <hr>
                    <h6 class="text-uppercase row">
                        <div class="col-md-6">
                            Infant Details
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-sm btn-behance addInfantButton" type="button">Add Infant</button>
                        </div>

                    </h6>
                    <table class="table table-sm">
                        <thead class="thead-dark">
                        <tr>

                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="30%">DOB</th>
                            <th width="30%">Travelling With</th>
                            <th width="5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="InfantListBody">

                        </tbody>
                    </table>
                    <hr>
                    <div class=" row mb-2">

                        <div class=" col-md-6">
                            <div class="form-group ">
                                <label for="">Remark</label>
                                <textarea name="remarks" id="remarks" cols="20" rows="4" class="form-control form-control-sm" required
                                 placeholder="Please mention reason for offline booking"></textarea>

                            </div>
                        </div>
                        <div class="col-md-6 ">

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Adult</label>
                                    <input type="number" class="form-control form-control-sm" step="0.01" id="pax_cost" value="{{ $data->sell_price }}" name="pax_cost" >

                                </div>
                                <div class="col-md-4">
                                    <label for="">Child</label>
                                    <input type="number" class="form-control form-control-sm" step="0.01" id="child_cost" value="{{ $data->sell_price }}" name="child_cost" readonly>

                                </div>
                                <div class="col-md-4">
                                    <label for="">Infant</label>
                                    <input type="number" class="form-control form-control-sm" step="0.01" id="infant_cost" value="{{ $data->infant }}" name="infant_charge" readonly>

                                </div>
                            </div>


                            Total Price Rs. <span class="font-weight-bold lg" id="totalPrice">{{ $data->sell_price }}</span>
                            <br>
                            <b>Display Price </b>
                            <input type="number" class="form-control form-control-sm" step="0.01" id="display_price" value="0" name="display_price">

                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success btn-sm">Book Now</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.airline').select2();
        $('.select2').select2();
        $('.destination').select2();
        $('.exclude_zero').select2();
        $(document).ready(function() {
            $('#fillWithTBACheckBox')
              $('#fillWithTBACheckBox').change(function() {
                if($(this).is(":checked")) {
                    var returnVal = confirm("Are you sure?");
                    if(returnVal){

                       $("input[name*='first_name']").val("TBA");
                       $("input[name*='last_name']").val("TBA");
                    }else{
                        return false;
                    }

                }else{
                    $("input[name*='first_name']").val("");
                }

    });
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('#agent-select2').change(function() {
                let agent_id = $('#agent-select2').val();
                $.ajax({
                    url: '/flight-tickets/ajax/agent-details',
                    data: {
                        agent_id: agent_id
                    },
                    dataType: 'JSON',
                    type: 'GET',
                    success: function(resp) {
                        console.log(resp);
                        $('#email').val(resp.email);
                        $('#mobile').val(resp.phone);
                    }
                })
            });

            $('#agent-select2').on('select2:selecting', function(e) {
                let data = e.params.args.data;
                $('#email').val(data.email);
                $('#mobile').val(data.phone);
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
            // $(document).on("change", ".dob", function(e) {
            //     let travel_date = new Date($('#travel_date_hidden').val());
            //     let current_dob = new Date($(e.target).val());
            //     var diff = new Date(current_dob - travel_date);

            //     var target = e.target;
            //     var parent = target.parentElement;

            //     // get days
            //     var days = parseInt(Math.abs(diff / 1000 / 60 / 60 / 24));
            //     alert(days);
            //     if (days > 730) {
            //         alert(days);
            //         $(parent).append("<span class='label label-important'> Hello</span>");
            //     }
            //     console.log(e);

            // });

            $(document).on("click", ".addButton", function() {
                let length = $('#PassengerListBody tr').length;

                $('#PassengerListBody').append('<tr> <td>' + ++length + '</td> <td> <select name="title[]" id="agent_id" class="form-control form-control-sm" required> <option value="Mr" selected> Mr</option> <option value="Mrs"> Mrs</option> <option value="Ms"> Ms</option> </select> </td> <td><input type="text" class="form-control form-control-sm"  required name="first_name[]" autocomplete="off"></td> <td><input type="text" class="form-control form-control-sm" required  name="last_name[]" autocomplete="off"></td> <td> <div class="btn-group"> <button class="btn btn-sm btn-red deleteBtn" type="button"> <i class="mdi mdi-trash-can"></i> </button></div> </td> </tr>');
                countPrice();
            });
            $(document).on("click", ".deleteBtn", function() {
                let adult_pax_no = $('#PassengerListBody >tr').length;
                console.log(adult_pax_no);
                if (adult_pax_no > 1) {
                    var tr = $(this).closest('tr').css("background-color", "#FF3700");
                    tr.fadeOut(700, function() {
                        tr.remove();
                        countPrice();
                        return false;
                    });
                }
            });

            $(document).on("click", ".addChildButton", function() {
                $('#ChildListBody').append('<tr><td><select name="child_title[]"class="form-control form-control-sm" required><option value="Mstr"> Mstr</option><option value="Miss"> Miss</option></select></td><td><input type="text" class="form-control form-control-sm"  required name="child_first_name[]" autocomplete="off"></td><td><input type="text" class="form-control form-control-sm"  required name="child_last_name[]" autocomplete="off"></td><td><div class="btn-group"><button class="btn btn-sm btn-red deleteChildBtn" type="button"><i class="mdi mdi-trash-can"></i></button></div></td></tr>');
                countPrice();
            });

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [year, month, day].join('-');
            }

            $(document).on("click", ".addInfantButton", function() {
                var min_dob = new Date($('#travel_date_hidden').val())
                min_dob.setDate(min_dob.getDate() - 720);
                min_dob = formatDate(min_dob);
                let travel_date = new Date($('#travel_date_hidden').val());
                travel_date = formatDate(travel_date);

                let adult_pax_no = $('#PassengerListBody >tr').length;
                let travellingOption = '';
                for (let i = 1; i <= adult_pax_no; i++) {
                    travellingOption += '<option value="' + i + '">Adult ' + i + '</option>'
                }
                console.log(travellingOption);

                $('#InfantListBody').append(`<tr><td><select name="infant_title[]"  class="form-control form-control-sm" required><option value="Mstr"> Mstr</option><option value="Miss"> Miss</option></select></td><td><input type="text" class="form-control form-control-sm"  required name="infant_first_name[]" autocomplete="off"></td><td><input type="text" class="form-control form-control-sm"  name="infant_last_name[]" required autocomplete="off"></td><td><input type="date" class="form-control form-control-sm dob"
             name="infant_dob[]"  min="${min_dob}" max="${travel_date}"
            autocomplete="off" required></td><td>
            <select class="form-control form-control-sm" name="infant_travelling_with[]" required>${travellingOption}</select>
            </td><td><div class="btn-group"><button class="btn btn-sm btn-red deleteInfantBtn" type="button"><i class="mdi mdi-trash-can"></i></button></div></td></tr>`);
                countPrice();
            });

            $(document).on("click", ".deleteInfantBtn", function() {
                var tr = $(this).closest('tr').css("background-color", "#FF3700");
                tr.fadeOut(700, function() {
                    tr.remove();
                    countPrice();
                    return false;
                });

            });
            $(document).on("click", ".deleteChildBtn", function() {
                var tr = $(this).closest('tr').css("background-color", "#FF3700");
                tr.fadeOut(700, function() {
                    tr.remove();
                    countPrice();
                    console.log("Delete Child Button");

                    return false;
                });

            });
            $('#pax_cost,#child_cost,#infant_cost').change(() => {
                countPrice();
            })

            function countPrice() {
                let adult_pax_no = $('#PassengerListBody >tr').length;
                let infant_pax_no = $('#InfantListBody > tr').length;
                let child_pax_no = $('#ChildListBody > tr').length;
                let adult_pax_cost = $('#pax_cost').val();
                let child_pax_cost = adult_pax_cost;
                let infant_pax_cost = $('#infant_cost').val();
                $('#child_cost').val(adult_pax_cost)


                let infant_cost = 0;
                let child_cost = 0;
                if (infant_pax_no > 0) {
                    infant_cost = infant_pax_no * infant_pax_cost;
                    console.log("current_infant_cost" + infant_cost);
                }
                if (child_pax_cost > 0) {
                    child_cost = child_pax_cost * child_pax_no;
                    console.log("current_child _cost" + infant_cost);
                }

                let adult_cost = adult_pax_no * adult_pax_cost;


                let total = adult_cost + infant_cost + child_cost;
                $('#totalPrice').html(total);
                $('#display_price').html(total);
            }


        });
        $('.bookNowBtn').click(function() {
            var air_ticket_id = $('#data-value').attr();
            console.log(air_ticket_id);
            $.ajax({
                url: '/flight-tickets/ajax/book-ticket-details?purchase_id=' + air_ticket_id,
                type: 'POST',
                dataType: 'JSON',
                success: function(resp) {
                    console.log(resp);
                }
            })
        })
    </script>

@endsection
