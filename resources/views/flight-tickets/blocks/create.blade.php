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
<div class="offset-2 col-md-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Block Ticket</h4>
            <p class="card-description">
                Block Ticket
            </p>
            <hr>

            <form action="{{ route('blocks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $data->id }}">
                <h6 class="text-uppercase">Agent Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Select Source</label>
                            <select name="agent_id" id="agent-select2" class="form-control form-control-sm  " required>
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
                            <input type="text" class="form-control form-control-sm" readonly="" name="pnr_no" value="{{ $data->airline->name }} - {{ $data->pnr}}">

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

                            <input type="text" class="form-control form-control-sm" readonly="" name="destinations" value="{{ $data->travel_date->format('d-m-Y') }} - {{ $data->departure_time}}">

                        </div>
                    </div>
                </div>

                <hr>
                <div class="form-group row">
                    <div class="col-md-6 form-group ">
                        <label for="">Quantity</label>

                        <input type="number" class="form-control" value="1" name="quantity">
                        <small> <strong class="text-success">Available Ticket {{ $data->available }}</strong></small>

                    </div>
                    <div class=" col-md-6">

                        <div class="form-group ">
                            <label for="">Remark</label>

                            <textarea name="remarks" id="remarks" cols="20" rows="4" class="form-control" required></textarea>



                        </div>
                    </div>
                </div>
                <hr>

                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm">Block Now</button>

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
        $("#agent-select2").select2({
            allowClear: true,
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
                    console.log(data);
                    return {
                        results: data
                    };
                },
                dataType: 'json',
                cache: true
            },
            minimumInputLength: 4,
        },{
            id:5,
            text: 'VishalTravels'
        });

        $('#agent-select2').on('select2:selecting', function(e) {
            let data = e.params.args.data;
            $('#email').val(data.email);
            $('#mobile').val(data.phone);
        });


        $('#agent_id').change(function() {
            let agent_id = $('#agent_id').val();

            $.ajax({
                url: '/flight-tickets/ajax/agent-details',
                data: {
                    agent_id: agent_id
                },
                dataType: 'JSON',
                type: 'GET',
                success: function(resp) {
                    $('#email').val(resp.email);
                    $('#mobile').val(resp.phone);
                }
            })
        })
    });
</script>

@endsection
