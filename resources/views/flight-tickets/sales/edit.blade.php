@extends('layouts.app')
@section('title','Sales')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link
rel="stylesheet"
 type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }

    .toast.toast-success{
        opacity: 1 !important;
        background-color: green
    }
    .toast-message{
        color: white ;
    }

</style>
@endsection
@section('contents')
<div class="offset-1 col-md-10 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Book Ticket</h4>
            <p class="card-description">
                Book Ticket {{ $data->bill_no }}
            </p>
            <hr>

            <form action="{{ route('bookings.update',$data->id) }}" method="POST">
                @csrf
                @method('put')
                <input type="hidden" name="ticket_id" value="{{ $data->id }}">
                <h6 class="text-uppercase">Agent Details</h6>
                <div class="row">

                    <div class="col-md-4">
                    
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Email Id </label>
                            <input type="text" class="form-control form-control-sm" readonly="" name="email" id="email" value="{{ $data->email }}">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Mobile No</label>
                            <input type="text" class="form-control form-control-sm" readonly="" name="mobile" id="mobile" value="{{ $data->phone }}">
                        </div>

                    </div>
                </div>
                <h6 class="text-uppercase">Flight Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Airline and PNR No</label>
                            <input type="text" class="form-control form-control-sm" readonly="" name="pnr_no" value="{{ $data->airline }} - {{ implode(",",json_decode($data->pnr,true)) }}">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Destination</label>
                            <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->src }} - {{ $data->dest}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Travel Date and Time</label>
                            <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value=" {{ \Carbon\Carbon::parse($data->departureDate)->format('Y-m-d') }}  {{ \Carbon\Carbon::parse($data->departureDate)->format('H:i:s') }}">
                        </div>
                    </div>
                </div>
                <!-- Adult Details start -->
                <h6 class="text-uppercase row">
                    <div class="col-md-6">
                        Adult Details
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th width="5%">Sl.No</th>
                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="30%">DOB</th>
                            <th width="10%">Edit</th>
                        </tr>
                    </thead>
                    <tbody id="PassengerListBody">
                        @foreach($book_details->where('type',1) as $key => $val)
                        <tr @if($val->is_refund == 1) class="bg-red" @endif data-id="{{$val->id}}">
                            <th width="5%">{{ 1 + $key }}</th>
                            @if($val->is_refund == 0)
                            <input type="hidden" name="adult_id[]" value="{{ $val->id }}">
                            @endif
                            <td>
                                <select name="title[]" id="title[]" class="form-control form-control-sm title"  @if($val->is_refund == 1) disabled @endif required>
                                    <option value="Mr" @if($val->title == 'Mr') selected @endif> Mr</option>
                                    <option value="Mrs" @if($val->title == 'Mrs') selected @endif> Mrs</option>
                                    <option value="Ms" @if($val->title == 'Ms') selected @endif> Ms</option>
                                </select>
                            </td>
                            <td><input type="text" required class="form-control form-control-sm first_name" name="first_name[]" value="{{ $val->first_name }}" @if($val->is_refund == 1) disabled @endif></td>
                            <td><input type="text" required class="form-control form-control-sm last_name" name="last_name[]" value="{{ $val->last_name}}" @if($val->is_refund == 1) disabled @endif> </td>
                            <td>
                                <input type="date"
                                       class="form-control form-control-sm dob"
                                       name="dob[]"
                                       value="@if($val->dob){{ $val->dob->format('Y-m-d') }}@endif"
                                       @if($val->is_refund == 1) disabled @endif
                                >
                            </td>

                            <td style="width:100px">
                                <button type="button" class="btn btn-info btn-sm update-pax-name">Update</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Adult Details end -->
                <hr>
                <!-- Child Details start -->
                <h6 class="text-uppercase row">
                    <div class="col-md-6">
                        Child Details
                    </div>
                    <div class="col-md-6 text-right">
                    <div class="col-md-6 text-right">

                    </div>
                </h6>
                <table class="table table-sm">
                    <thead>
                        <tr>

                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name </th>
                            <th width="30%">DOB </th>
                            <th width="10%">Edit</th>
                        </tr>
                    </thead>
                    <tbody id="ChildListBody">
                        @foreach($book_details->where('type',2) as $key => $val)
                        <tr  @if($val->is_refund == 1) class="bg-red" @endif data-id="{{$val->id}}">
                            <input type="hidden"
                                   name="child_id[]"
                                   value="{{ $val->id }}"
                                   class="id"
                            >
                            <td>
                                <select name="child_title[]" id="agent_id" class="form-control form-control-sm title" required>
                                    <option value="Master" @if($val->title == 'Master') selected @endif> Master</option>
                                    <option value="Ms" @if($val->title == 'Ms') selected @endif> Ms</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm first_name" name="child_first_name[]" required value="{{ $val->first_name }}" /></td>
                            <td><input type="text" class="form-control form-control-sm last_name" name="child_last_name[]" required value="{{ $val->last_name }}" /></td>
                            <td><input type="date" class="form-control form-control-sm dob" name="child_dob[]"  value="@if($val->dob){{ $val->dob->format('Y-m-d') }}@endif" /></td>
                            <td style="width:100px">
                                <button type="button" class="btn btn-info btn-sm update-pax-name">Update</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <!-- Child Details end -->
                <!-- Infant Details start -->
                <h6 class="text-uppercase row">
                    <div class="col-md-6">
                        Infant Details
                    </div>
                    <div class="col-md-6 text-right">

                    </div>

                </h6>
                <table class="table table-sm">
                    <thead>
                        <tr>

                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th width="30%">DOB</th>
                            <th width="30%">Travelling With</th>
                        </tr>
                    </thead>
                    <tbody id="InfantListBody">



                    <tbody id="InfantListBody">
                        @foreach($book_details->where('type',3) as $key => $val)
                        <tr>

                            <input type="hidden" name="infant_id[]" value="{{ $val->id }}">
                            <td>
                                <select name="infant_title[]" id="agent_id" class="form-control form-control-sm" required>
                                    <option value="Master" @if($val->title == 'Master') selected @endif> Master</option>
                                    <option value="Ms" @if($val->title == 'Ms') selected @endif> Ms</option>

                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" name="infant_first_name[]" value="{{ $val->first_name }}" required /></td>
                            <td><input type="text" class="form-control form-control-sm" name="infant_last_name[]" value="{{ $val->last_name }}" required /></td>


                            <td><input type="date" class="form-control form-control-sm" name="infant_dob[]" value="{{ date('Y-m-d', strtotime($val->dob)) }}" required /></td>
                            <td><input type="text" class="form-control form-control-sm" name="infant_travelling_with[]" required value="{{ $val->travelling_with }}" /></td>




                        </tr>
                        @endforeach


                    </tbody>
                </table>

                <!-- Infant Details end -->


                <hr>
                <div class="form-group row">

                    <div class=" col-md-6">
                        <div class="form-group ">
                            <label for="">Remark</label>
                            <textarea name="remarks" id="remarks" cols="20" rows="2" class="form-control form-control-sm">{{ $data->remark }}</textarea>
                        </div>
                    </div>
                    <div class=" col-md-6">
                        <div class="row">
                            @can('sales_price_update')
                            <div class="col-md-4">
                                <label for="">Adult</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="pax_cost"   value="{{ $data->pax_price }}" name="pax_cost" >
                            </div>
                            <div class="col-md-4">
                                <label for="">Child</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="child_cost" value="{{ $data->child_charge }}" name="child_cost">
                            </div>

                            <div class="col-md-4">
                                <label for="">Infant</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="infant_cost" value="{{ $data->infant_charge }}" name="infant_charge" >
                            </div>

                            <div class="col-md-12">
                                Total Price Rs. <span class="font-weight-bold lg" id="totalPrice">{{ $data->pax_price * $data->adults  + $data->child_charge * $data->child  + $data->infant_charge * $data->infants }}</span>
                            </div>
                            @endcan
                        </div>
                    </div>

                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-4">
                    </div>




                </div>
                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm">Update Now</button>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>

    $('.select2').select2();
    $('.airline').select2();
    $('.destination').select2();
    $('.exclude_zero').select2();
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $('#agent_id').change(function() {
            let agent_id = $('#agent_id').val();
            $.ajax({
                url: '/ajax/agent-details',
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
        })
        $(document).on("click", ".addButton", function() {

            $('#PassengerListBody').append('<tr> <td> <select name="title[]" id="agent_id" class="form-control form-control-sm"> <option value="Mr" selected> Mr</option> <option value="Mrs"> Mrs</option> <option value="Ms"> Ms</option> </select> </td> <td><input type="text" class="form-control form-control-sm"  name="first_name[]"></td> <td><input type="text" class="form-control form-control-sm"  name="last_name[]"></td> <td> <div class="btn-group"> <button class="btn btn-sm btn-red deleteBtn" type="button"> <i class="mdi mdi-trash-can"></i> </button></div> </td> </tr>');
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
            $('#InfantListBody').append('<tr><td><select name="child_title[]" id="agent_id" class="form-control form-control-sm"><option value="Mr" selected> Mr</option><option value="Mrs"> Mrs</option><option value="Ms"> Ms</option></select></td><td><input type="text" class="form-control form-control-sm"  name="child_first_name[]"></td><td><input type="text" class="form-control form-control-sm"  name="child_last_name[]"></td><td><input type="text" class="form-control form-control-sm"  name="child_dob[]"></td><td><input type="text" class="form-control form-control-sm"  name="child_travelling_with[]"></td><td><div class="btn-group"><button class="btn btn-sm btn-red deleteChildBtn" type="button"><i class="mdi mdi-trash-can"></i></button></div></td></tr>');
            countPrice();
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
        $('#pax_cost,#child_cost,#infant_cost').keyup(() => {
            countPrice();
            updateChildValue();
        })
        function updateChildValue(){
            let adult = $('#pax_cost').val();
            $('#child_cost').val(adult);
        }

        function countPrice() {

            let adult_pax_no = $('#PassengerListBody >tr').length;
            let child_pax_no = $('#ChildListBody > tr').length;
            let infant_pax_no = $('#InfantListBody > tr').length;
            let adult_pax_cost = $('#pax_cost').val();
            let child_pax_cost = $('#child_cost').val();
            let infant_pax_cost = $('#infant_cost').val();


            let infant_cost = 0;
            let child_cost = 0;
            if (infant_pax_no > 0) {
                infant_cost = infant_pax_no * infant_pax_cost;
                console.log("current_infant_cost" + infant_cost);
            }

            if (child_pax_no > 0) {
                child_cost = child_pax_no * child_pax_cost;
                console.log("current_infant_cost" + infant_cost);
            }
            console.log("current_infant_cost" + infant_cost);
            let adult_cost = adult_pax_no * adult_pax_cost;
            console.log("infant" + infant_cost);

            let total = adult_cost + infant_cost + child_cost;
            $('#totalPrice').html(total);
        }


        $("#agent-select2").select2({
            allowClear: false,
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                return data.html;
            },
            templateSelection: function(data) {
                return data.text;
            },
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


    });


    $('.bookNowBtn').click(function() {
        var air_ticket_id = $('#data-value').attr();
        console.log(air_ticket_id);
        $.ajax({
            url: '/ajax/book-ticket-details?purchase_id=' + air_ticket_id,
            type: 'POST',
            dataType: 'JSON',
            success: function(resp) {
                console.log(resp);
            }
        })
    })


    $('.update-pax-name').click(function() {
        let book_details_id = $(this).parent().parent().data('id');
        let title            = $(this).parent().parent().find('.title').val();
        let first_name      = $(this).parent().parent().find('.first_name').val();
        let last_name       = $(this).parent().parent().find('.last_name').val();
        let dob             = $(this).parent().parent().find('.dob').val();

        $.ajax({
            url: '/flight-tickets/ajax/update-pax-name',
            type: 'POST',
            dataType: 'JSON',
            data: {
                title:title,
                book_details_id : book_details_id,
                first_name : first_name,
                last_name : last_name,
                dob : dob
            },
            success: function(resp) {
                if(resp.success){
                    toastr.success('Successfully updated pax name', { timeOut: 2000000 });
                }

            }
        })
    });
</script>

@endsection
