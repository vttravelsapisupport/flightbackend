@extends('layouts.app')
@section('title','PNR Name List')
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
    <div class="offset-2 col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Book Ticket</h4>
                <p class="card-description">
                    Book Ticket
                </p>
                <hr>

                <form action="{{ route('book-ticket.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $data->id }}">
                    <h6 class="text-uppercase">Agent Details</h6>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group ">
                                <label >Select Source</label>
                                <select name="agent_id" id="agent_id" class="form-control form-control-sm" required>
                                    <option value=""> Select Source</option>
                                    @foreach($agents as $key => $value)
                                        <option value="{{ $key }}">{{ ucwords($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Email Id </label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="email" id="email">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for=""  >Mobile No</label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="mobile" id="mobile">
                            </div>

                        </div>
                    </div>
                    <h6 class="text-uppercase">Flight Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Airline and PNR No</label>
                                <input type="text" class="form-control form-control-sm" readonly="" name="pnr_no" value="{{ $data->airline->name }} - {{ $data->pnr}}">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Destination</label>

                                <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->destination->name }}">


                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Travel Date and Time</label>

                                <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->travel_date->format('d-m-Y') }} - {{ $data->departure_time}}">

                            </div>
                        </div>
                    </div>
                     <h6 class="text-uppercase">Passenger Details</h6>
                    <table class="table table-sm">
                        <thead>
                        <tr>

                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="PassengerListBody">
                            <tr>

                                <td>
                                    <select name="title[]" id="agent_id" class="form-control form-control-sm">

                                        <option value="Mr" selected> Mr</option>
                                        <option value="Mrs"> Mrs</option>
                                        <option value="Ms"> Ms</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-control-sm"  name="first_name[]"></td>
                                <td><input type="text" class="form-control form-control-sm"  name="last_name[]"></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-red deleteBtn" type="button">
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                        <button class="btn btn-sm btn-red addButton" type="button">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>

                                </td>

                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6 form-group ">
                            <label for="" >Infant Details</label>

                                <textarea name="infant_detail" id="infant_detail" cols="30"  rows="2"
                                          class="form-control form-control-sm">
                                 </textarea>


                        </div>
                        <div class=" col-md-6">

                            <div class="form-group ">
                                <label for="" >Remark</label>

                                     <textarea name="remarks" id="remarks" cols="20"  rows="2"
                                               class="form-control form-control-sm" >

                                     </textarea>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="">Total</label>
                            <input type="number" class="form-control" step="0.01" id="TotalAmount"  value="{{ $data->sell_price }}" name="totalAmount" readonly="">
                            <input type="hidden" class="form-control" step="0.01" id="OneRate" readonly="" value="{{ $data->sell_price }}" name="OneRate">
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
        $('.destination').select2();
        $('.exclude_zero').select2();
        $(document).ready(function() {
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('#agent_id').change(function() {
                console.log('agent id');
                let agent_id = $('#agent_id').val();
                $.ajax({
                    url: '/ajax/agent-details',
                    data: { agent_id: agent_id},
                    dataType: 'JSON',
                    type: 'GET',
                    success:function(resp){
                        console.log(resp);
                        $('#email').val(resp.email);
                        $('#mobile').val(resp.phone);
                    }
                })
            })
            $(document).on("click",".addButton",function() {
                 let tr =  $('#PassengerListBody tr').length + 1;
                 console.log(tr);
                 let OneRate = $('#OneRate').val();
                 $('#TotalAmount').val(tr*OneRate);
                 let totalAmount = parseInt($('#TotalAmount').val());




                  $('#FinalRate').val();



                $('#PassengerListBody').append('<tr> <td> <select name="title[]" id="agent_id" class="form-control form-control-sm"> <option value="Mr" selected> Mr</option> <option value="Mrs"> Mrs</option> <option value="Ms"> Ms</option> </select> </td> <td><input type="text" class="form-control form-control-sm"  name="first_name[]"></td> <td><input type="text" class="form-control form-control-sm"  name="last_name[]"></td> <td> <div class="btn-group"> <button class="btn btn-sm btn-red deleteBtn" type="button"> <i class="mdi mdi-trash-can"></i> </button> <button class="btn btn-sm btn-red addButton" type="button"> <i class="mdi mdi-plus"></i> </button> </div> </td> </tr>');
            });
            $(document).on("click",".deleteBtn",function() {
                let tr = $(this).closest('tr');

                tr.css("background-color","#FF3700");
                tr.fadeOut(700, function(){
                    tr.remove();
                });
                let tr1 =  $('#PassengerListBody tr').length;
                console.log(tr1);
                let OneRate = $('#OneRate').val();
                let totalAmount = $('#TotalAmount').val();
                $('#TotalAmount').val(totalAmount - OneRate);
                return false;
            });


        });
        $('.bookNowBtn').click(function(){
            var air_ticket_id = $('#data-value').attr();
            console.log(air_ticket_id);
            $.ajax({
                url: '/ajax/book-ticket-details?purchase_id='+air_ticket_id,
                type: 'POST',
                dataType:'JSON',
                success:function(resp){
                    console.log(resp);
                }
            })
        })
    </script>

@endsection
