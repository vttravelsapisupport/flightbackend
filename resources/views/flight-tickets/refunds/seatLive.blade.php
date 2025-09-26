@extends('layouts.app')
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
            <h4 class="card-title text-uppercase">Seat Live</h4>
            <p class="card-description">
                Seat Live
            </p>
            <hr>
            <div id="responseMessage"></div>
            <form id="refundCancellationForm" action="{{ route('refund-cancellation-request') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $data->id }}">
                <input type="hidden" name="book_id" value="{{ $data->book_ticket_id }}">

                <h6 class="text-uppercase">Agent Details</h6>
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Account </label>
                            <input type="hidden" name="agent_id" value="{{ $data->agent_id }}">
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ ucwords($data->agent->company_name) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Email Id </label>
                            <input type="text" class="form-control form-control-sm" disabled="" value="{{  ucwords($data->agent->email) }}">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Mobile No</label>
                            <input type="text" class="form-control form-control-sm" disabled="" value="{{ ucwords($data->agent->phone) }}">
                        </div>

                    </div>
                </div>
                <h6 class="text-uppercase">Flight Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Airline and PNR No</label>
                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $data->airline }} {{ $data->pnr }}">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Destination</label>

                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $data->destination }}">


                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="">Travel Date and Time</label>

                            <input type="text" class="form-control form-control-sm" readonly="" value="{{ $data->travel_date->format('d-m-Y') }} {{ $data->travel_time }}">

                        </div>
                    </div>
                </div>
                <h6 class="text-uppercase">Passenger Details</h6>
                <table class="table table-sm table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">Select</th>
                            <th width="10%">Title</th>
                            <th width="30%">First Name</th>
                            <th width="30%">Last Name</th>
                            <th>DOB</th>
                            <th>Travelling With</th>
                            <th>Type</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->passenger_details as $key => $value)
                        <tr class="@if($value->is_refund == 1) bg-red @elseif($value->is_refund == 2) bg-info @endif">
                            <td>

                                <input type="checkbox" value="{{ $value->id }}" name="refund[]"
                                    class="
                                       @if($value->is_refund != 1 && $value->type == 1 )
                                       refund_ids
                                       @endif
                                       @if($value->is_refund != 1 && $value->type == 2 )
                                       refund_ids_child
                                       @endif
                                       @if($value->is_refund != 1 && $value->type == 3 )refund_ids_infant @endif"
                                    @if($value->is_refund == 1) checked @endif
                                @if($value->is_refund == 1 || $value->is_refund == 2 )disabled @endif

                                >
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

                        <textarea name="agent_remarks" id="remarks" cols="20" rows="2" class="form-control form-control-sm" required></textarea>




                    </div>
                </div>
                <hr>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Adult</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="TotalAmount" value="{{ $data->pax_price }}" name="totalAmount" readonly="">

                            </div>
                            <div class="col-md-3">
                                <label for="">Child</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="childCharge" value="{{ $data->child_charge }}" name="childCharge" readonly="">

                            </div>
                            <div class="col-md-3">
                                <label for="">Infant</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="infantCharge" value="{{ $data->infant_charge }}" name="infantCharge" readonly="">

                            </div>
                            <div class="col-md-3">
                                <label for="">Total Amount</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" id="TotalSaleAmount" value="{{ $data->pax_price * $data->adults  + $data->child_charge * $data->child  + $data->infant_charge * $data->infants }}" name="TotalSaleAmount" readonly="">

                            </div>
                            <div class="col-md-3">
                                <label for="">Wallet Type</label>
                                <select name="wallet_type" id="wallet_type" class="form-control">
                                    <option value="1" @if($data->purchase_entry->airline_id != 3) selected @endif>Main Wallet</option>
                                    <option value="2"
                                        @if($data->purchase_entry->airline_id != 3)
                                        disabled
                                        @else
                                        selected
                                        @endif >Credit Wallet</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
                                <input type="text" class="form-control form-control-sm" step="0.01" value="{{isset($cancellationCharge) ? $cancellationCharge : 0 }}" name="refund_pax_price" aria-invalid="refund_pax_price" id="refund_pax_price">
                            </div>
                            <div class="col-md-4">
                                <label for="">Total Charges</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="" name="total_refund" id="total_refund" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="">Refund Amount </label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="" name="balance" id="balance" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="offset-6 col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-uppercase">Supplier</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="">Cancellation Charge</label>
                                <input type="text" class="form-control form-control-sm" step="0.01" value="0" name="supplier_refund_pax_price" aria-invalid="refund_pax_price" id="supplier_refund_pax_price">
                            </div>

                            <div class="col-md-4">
                                <label for="">Total Charges</label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="" name="supplier_total_refund" id="supplier_total_refund" readonly>

                            </div>
                            <div class="col-md-4">
                                <label for="">Refund Amount </label>
                                <input type="number" class="form-control form-control-sm" step="0.01" value="" name="supplier_balance" id="supplier_balance" readonly>

                            </div>
                        </div>

                    </div>
                </div>

                @if($data->purchase_entry->airline_id == 3)
                <div class="alert alert-warning" role="alert">
                    Refund of this will be added to the Gofirst Wallet of the agent.
                </div>
                @endif
                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm">Cancelled Request</button>

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
    // CSRF Token setup
    $('#refundCancellationForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                let alertClass = response.success ? 'success' : 'danger';
                $('#responseMessage').html(`
                <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                    ${response.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            },
            error: function(xhr) {
                let errorMessage = "Something went wrong!";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '<ul>';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += `<li>${value[0]}</li>`;
                    });
                    errorMessage += '</ul>';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#responseMessage').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${errorMessage}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            }
        });
    });

    calculatePrice();
    calculateBalance();

    $('#supplier_refund_pax_price').keyup((e) => {
        supplierCalculatePrice();
        supplierCalculateBalance();
    })
    $('#refund_pax_price').keyup((e) => {
        calculatePrice();
        calculateBalance();
    })
    $('.refund_ids,.refund_ids_infant,.refund_ids_child').change(function() {
        calculatePrice();
        calculateBalance();
    })

    function supplierCalculatePrice() {
        let refund_pax_price = $('#supplier_refund_pax_price').val();

        let adult_count = 0
        let infant_count = 0
        let child_count = 0

        $('.refund_ids:checked').each((e) => {
            adult_count++;
        })

        $('.refund_ids_infant:checked').each((e) => {
            infant_count++;
        })

        $('.refund_ids_child:checked').each((e) => {
            child_count++;
        });

        $('#adult_count').val(adult_count);
        $('#child_count').val(child_count);
        $('#infant_count').val(infant_count);

        let infant_amount = $('#infantCharge').val();
        let child_amount = $('#childCharge').val();

        $('#supplier_total_refund').val((refund_pax_price * adult_count) + (refund_pax_price * child_count));
    }

    function supplierCalculateBalance() {
        let total_refund = $('#supplier_total_refund').val();
        let adult_count = 0
        $('.refund_ids:checked').each((e) => {
            adult_count++;
        })
        let infant_count = 0
        $('.refund_ids_infant:checked').each((e) => {
            infant_count++;
        })
        let child_count = 0
        $('.refund_ids_child:checked').each((e) => {
            child_count++;
        })

        // refund_ids_child
        let total_sale_amount = $('#TotalAmount').val();
        let infant_amount = $('#infantCharge').val();
        let child_amount = $('#childCharge').val();

        let balance = (total_sale_amount * adult_count) + (infant_amount * infant_count) + (child_amount * child_count) - total_refund;
        $('#supplier_balance').val(balance);
    }

    function calculatePrice() {
        let refund_pax_price = $('#refund_pax_price').val();

        let adult_count = 0
        let infant_count = 0
        let child_count = 0

        $('.refund_ids:checked').each((e) => {
            adult_count++;
        })

        $('.refund_ids_infant:checked').each((e) => {
            infant_count++;
        })

        $('.refund_ids_child:checked').each((e) => {
            child_count++;
        });

        $('#adult_count').val(adult_count);
        $('#child_count').val(child_count);
        $('#infant_count').val(infant_count);

        let infant_amount = $('#infantCharge').val();
        let child_amount = $('#childCharge').val();

        $('#total_refund').val((refund_pax_price * adult_count) + (refund_pax_price * child_count));
    }

    function calculateBalance() {
        let total_refund = $('#total_refund').val();
        let adult_count = 0
        $('.refund_ids:checked').each((e) => {
            adult_count++;
        })
        let infant_count = 0
        $('.refund_ids_infant:checked').each((e) => {
            infant_count++;
        })
        let child_count = 0
        $('.refund_ids_child:checked').each((e) => {
            child_count++;
        })

        // refund_ids_child
        let total_sale_amount = $('#TotalAmount').val();
        let infant_amount = $('#infantCharge').val();
        let child_amount = $('#childCharge').val();

        let balance = (total_sale_amount * adult_count) + (infant_amount * infant_count) + (child_amount * child_count) - total_refund;
        $('#balance').val(balance);
    }
</script>

@endsection