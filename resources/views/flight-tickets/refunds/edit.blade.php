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
            <h4 class="card-title text-uppercase">Refund Ticket Details </h4>
            <p class="card-description">
                Refund Ticket Details of <strong>{{ $datas->bookTicket->bill_no }}</strong>
            </p>
            <hr>
            <form action="{{ route('refunds.update',$datas->id) }}" method="POST">
                @csrf
                @method('put')
                <h6 class="text-uppercase">Agent Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Account </label>
                            <input type="hidden" name="agent_id" value="{{ $datas->agent_id }}">
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ ucwords($datas->agent->company_name) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Email Id </label>
                            <input type="text" class="form-control form-control-sm" disabled="" value="{{  ucwords($datas->agent->email) }}">
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
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $datas->bookTicket->airline }} {{ $datas->bookTicket->pnr }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Destination</label>
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $datas->bookTicket->destination }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Travel Date and Time</label>
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $datas->bookTicket->travel_date->format('d-M-y') }} {{ $datas->bookTicket->travel_time }}">
                        </div>
                    </div>
                </div>
                <h6 class="text-uppercase">Passenger Details</h6>
                <table class="table table-sm table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Selected</th>
                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th>DOB</th>
                            <th>Travelling With</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($passengers as $key => $value)
                        <tr class="@if($value->is_refund == 1)bg-red @endif">
                            <td>
                                <input type="checkbox" value="{{ $value->id }}" name="refund[]" class="@if( $value->type == 1 )refund_ids @endif @if( $value->type == 2 )refund_ids_child @endif @if( $value->type == 3 )refund_ids_infant @endif" checked disabled>
                            </td>
                            <td>
                                {{ $value->title }}
                            </td>
                            <td> {{ $value->first_name }}</td>
                            <td>{{ $value->last_name }}</td>
                            <td>@if($value->dob){{ $value->dob->format('d-M-Y') }} @endif</td>
                            <td>{{ $value->travelling_with }}</td>
                            <td>@if($value->type == 1)
                                Adult
                                @elseif($value->type == 2)
                                Child
                                @else
                                Infant
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="form-group row">
                    <div class=" col-md-12">
                        <label for="">Remark</label>
                        <strong>{{ $datas->remarks}}</strong>
                    </div>
                </div>
                <hr>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Adult</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="TotalAmount" value="{{ $datas->bookTicket->pax_price }}" name="totalAmount" readonly="">
                            </div>
                            <div class="col-md-3">
                                <label for="">Child</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="childCharge" value="{{ $datas->bookTicket->child_charge }}" name="childCharge" readonly="">
                            </div>
                            <div class="col-md-3">
                                <label for="">Infant</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="infantCharge" value="{{ $datas->bookTicket->infant_charge }}" name="infantCharge" readonly="">
                            </div>                            
                            <div class="col-md-3">
                                <label for="">Total Amount</label>
                                <input type="text" class="form-control form-control-sm" step="0.01" value="{{ $datas->total_refund }}" name="total_amount" aria-invalid="total_amount" id="total_amount" readonly="">
                            </div>                            
                        </div>
                    </div>
                    @can('refunds charges update')
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-uppercase">Agent</h6>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" id="adult_count" name="adult_count">
                                <input type="hidden" id="infant_count" name="infant_count">
                                <input type="hidden" id="child_count" name="child_count">
                                <label for="">Cancellation Charge</label>
                                <input type="text" class="form-control form-control-sm" step="0.01" value="{{ $datas->pax_cost }}" name="refund_pax_price" aria-invalid="refund_pax_price" id="refund_pax_price">
                            </div>
                            <div class="col-md-4">
                                <label for="">Total Charges</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="{{ ($datas->pax_cost * $datas->adult) + ($datas->pax_cost * $datas->child) }}" name="agent_total_refund" id="agent_total_refund" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="">Refund Amount </label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="{{ $datas->total_refund }}" name="balance" id="balance" readonly>
                            </div>
                        </div>
                    </div>                    
                    <div class="offset-6 col-md-6" style="margin-top: 1rem;">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-uppercase">Supplier</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="">Cancellation Charge</label>
                                <input type="text" class="form-control form-control-sm" step="0.01" value="{{ $datas->supplier_refund_pax_price }}" name="supplier_refund_pax_price" aria-invalid="refund_pax_price" id="supplier_refund_pax_price">
                            </div>
                            <div class="col-md-4">
                                <label for="">Total Charges</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="{{ ($datas->supplier_refund_pax_price * $datas->adult) + ($datas->pax_cost * $datas->child) }}" name="supplier_total_refund" id="supplier_total_refund" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="">Refund Amount </label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="{{ $datas->supplier_total_refund }}" name="supplier_balance" id="supplier_balance" readonly>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
                @can('refunds charges update')
                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </div>
                </div>
                @endcan
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    
    $(document).on('change', '#refund_pax_price', function() {
        calculatePrice();
        calculateBalance();
    })

    $(document).on('change', '#supplier_refund_pax_price', function() {
        calculateSupplierPrice();
        calculateSupplierBalance();
    })
    
    $(document).on('change', '.refund_ids', function() {
        calculatePrice();
        calculateBalance();
    })

    function calculatePrice() {
        let refund_pax_price = $('#refund_pax_price').val();
        let adult_count      = 0;
        let infant_count     = 0;
        let child_count      = 0;

        $('.refund_ids:checked').each((e)        => { adult_count++;  });
        $('.refund_ids_infant:checked').each((e) => { infant_count++; });
        $('.refund_ids_child:checked').each((e)  => { child_count++;  });

        $('#adult_count').val(adult_count);
        $('#child_count').val(child_count);
        $('#infant_count').val(infant_count);

        let infant_amount = $('#infantCharge').val();
        let child_amount  = $('#childCharge').val();

        $('#agent_total_refund').val((refund_pax_price * adult_count) + (refund_pax_price * child_count));
    }

    function calculateBalance() {
        let total_refund      = $('#agent_total_refund').val();
        let total_sale_amount = $('#TotalAmount').val();
        let infant_amount     = $('#infantCharge').val();
        let child_amount      = $('#childCharge').val();        
        let adult_count       = 0;
        let infant_count      = 0;
        let child_count       = 0;

        $('.refund_ids:checked').each((e)        => { adult_count++;  });        
        $('.refund_ids_infant:checked').each((e) => { infant_count++; });        
        $('.refund_ids_child:checked').each((e)  => { child_count++;  });        

        let balance = (total_sale_amount * adult_count) + (infant_amount * infant_count) + (child_amount * child_count) - total_refund;

        // let balance = total_sale_amount * count - total_refund;
        $('#balance').val(balance);
    }

    function calculateSupplierPrice() {
        let refund_pax_price = $('#supplier_refund_pax_price').val();
        let adult_count      = 0;
        let infant_count     = 0;
        let child_count      = 0;

        $('.refund_ids:checked').each((e)        => { adult_count++;  });
        $('.refund_ids_infant:checked').each((e) => { infant_count++; });
        $('.refund_ids_child:checked').each((e)  => { child_count++;  });

        $('#adult_count').val(adult_count);
        $('#child_count').val(child_count);
        $('#infant_count').val(infant_count);

        let infant_amount = $('#infantCharge').val();
        let child_amount  = $('#childCharge').val();

        $('#supplier_total_refund').val((refund_pax_price * adult_count) + (refund_pax_price * child_count));
    }

    function calculateSupplierBalance() {
        let total_refund      = $('#supplier_total_refund').val();
        let total_sale_amount = $('#TotalAmount').val();
        let infant_amount     = $('#infantCharge').val();
        let child_amount      = $('#childCharge').val(); 
        let adult_count       = 0;
        let infant_count      = 0;
        let child_count       = 0;

        $('.refund_ids:checked').each((e)        => { adult_count++;  });        
        $('.refund_ids_infant:checked').each((e) => { infant_count++; });       
        $('.refund_ids_child:checked').each((e)  => { child_count++;  });        

        let balance = (total_sale_amount * adult_count) + (infant_amount * infant_count) + (child_amount * child_count) - total_refund;        
        $('#supplier_balance').val(balance);
    }
</script>

@endsection