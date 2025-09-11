@extends('layouts.app')
@section('title','Refunds')
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
    <div class=" col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Refund Ticket Details   </h4>
                <p class="card-description">
                    Refund Ticket Details of <strong>{{ $datas->bookTicket->bill_no }}</strong>
                </p>
                <hr>



                    <h6 class="text-uppercase">Agent Details</h6>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group ">
                                <label >Account </label>
                                <input type="hidden" name="agent_id" value="{{ $datas->agent_id }}">
                                <input type="text" class="form-control form-control-sm" readonly=""  value="{{ ucwords($datas->agent->company_name) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Email Id </label>
                                <input type="text" class="form-control form-control-sm" disabled=""  value="{{  ucwords($datas->agent->email) }}">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Mobile No</label>
                                <input type="text" class="form-control form-control-sm" disabled="" value="{{ ucwords($datas->agent->phone) }}">
                            </div>

                        </div>
                    </div>
                    <h6 class="text-uppercase">Flight Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="">Airline and PNR No</label>
                                <input type="text" class="form-control form-control-sm" readonly=""  value="{{ $datas->bookTicket->airline }} {{ $datas->bookTicket->pnr }}">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Destination</label>

                                <input type="text" class="form-control form-control-sm" readonly=""  value="{{ $datas->bookTicket->destination }}" >


                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label for="" >Travel Date and Time</label>

                                <input type="text" class="form-control form-control-sm" readonly=""  value="{{ $datas->bookTicket->travel_date->format('d-M-y') }} {{ $datas->bookTicket->travel_time }}" >

                            </div>
                        </div>
                    </div>
                    <h6 class="text-uppercase">Passenger Details</h6>
                    <table class="table table-sm table-bordered text-center">
                        <thead class="thead-dark">
                        <tr>

                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>

                        </tr>
                        </thead>
                        <tbody >
                        @foreach($passengers as $key => $value)
                        <tr class="@if($value->is_refund == 1)bg-red @endif">

                            <td>
                                {{ $value->title }}
                            </td>
                            <td> {{ $value->first_name }}</td>
                            <td>{{ $value->last_name }}</td>



                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr>

                    <div class="form-group row">

                        <div class=" col-md-12">


                                <label for="" >Remark</label>
                                <strong>{{ $datas->remarks}}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class=" row">
                        <div class="col-md-4">
                            <label for="">Cancellation Charge (Per Pax)</label>
                            <strong>Rs {{ $datas->pax_cost }}</strong>
                        </div>
                        <div class="col-md-4">
                            <label for="">Total Refund Amount</label>
                            <strong>Rs {{ $datas->total_refund }}</strong>
                        </div>



                    </div>




            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
       $('#refund_pax_price').change((e) => {
           calculatePrice();
           calculateBalance();
       })
       $('.refund_ids').change(function () {
           calculatePrice();
           calculateBalance();
       })
       function calculatePrice(){
           let refund_pax_price = $('#refund_pax_price').val();
           let count = 0
           $('.refund_ids:checked').each((e) => {
               count++;
           })
           $('#total_refund').val(refund_pax_price * count);
       }
       function calculateBalance(){
           let total_refund = $('#total_refund').val();
           let count = 0
           $('.refund_ids:checked').each((e) => {
               count++;
           })
           let total_sale_amount = $('#TotalAmount').val();
           console.log(total_sale_amount);
           let balance = total_sale_amount * count - total_refund;
           $('#balance').val(balance);
       }


    </script>

@endsection
