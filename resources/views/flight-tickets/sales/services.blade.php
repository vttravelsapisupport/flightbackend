@extends('layouts.app')
@section('title','Sales')
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
                <h4 class="card-title text-uppercase">Services</h4>
                <p class="card-description">Services {{ $data->bill_no }}</p>
                <hr>
                <table class="table table-sm">
                    <thead>
                    <tr class="thead-dark">
                        <th>Booked On</th>
                        <th>Reference No.</th>
                        <th>Agent</th>
                        <th>Price</th>
                    </tr>
                    <tr>
                        <td>{{ $data->created_at->format('d-m-Y ') }} at {{ $data->created_at->format('H:i:s') }}</td>
                        <td>{{ $data->bill_no }}</td>
                        <td>
                            <a href="{{ $data->agent->id }}">
                                @if($data->agent->company_name){{ $data->agent->company_name }}
                                @else
                                    {{ $data->agent->contact_name }}
                                @endif
                            </a>
                        </td>
                        <td>
                            Rs. {{ $data->pax_price * $data->adults}}
                        </td>
                    </tr>
                    <tr class="thead-dark">
                        <th>PNR NO</th>
                        <th>Flight</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                    </tr>
                    <tr>
                        <td>{{ $data->pnr }}</td>
                        <td> {{ $data->airline }} ( {{ $data->purchase_entry->flight_no }} )</td>
                        <td>
                            {{ $data->destinationDetails->origin->name }} ( {{ $data->destinationDetails->origin->code }} ) <br> {{ $data->purchase_entry->travel_date->format('d-m-Y') }} {{ $data->purchase_entry->departure_time }}

                        </td>
                        <td>
                            {{ $data->destinationDetails->destination->name }} ( {{ $data->destinationDetails->destination->code }} ) <br> {{ $data->purchase_entry->travel_date->format('d-m-Y') }} {{ $data->purchase_entry->arrival_time }}
                        </td>
                    </tr>
                    </thead>
                </table>
                <h6>Passenger Details</h6>
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($book_ticket_details as $key => $val)
                        <tr @if($val->is_refund == 1) class="bg-red"  @endif>
                            <td >
                                &nbsp; {{ 1 +$key }}.
                            </td>
                            <td >
                                {{ $val->title }}
                            </td>
                            <td>
                                {{ $val->first_name }}
                            </td>
                            <td>
                                {{ $val->last_name }}
                            </td>

                        </tr>
                    </tbody>
                    @endforeach
                </table>
                <p>
                    {{ $data->remark }}
                </p>
                <form action="{{ url('flight-tickets/sales/'.$id.'/services') }}" method="POST">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Services</label>
                          <select name="additional_service_id" id="additional_service_id" class="form-control">
                              <option value="">Select Service</option>
                              @foreach($additionalService as $key => $val)
                              <option value="{{$key}}">{{$val}}</option>
                              @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Amount</label>
                           <input type="text" class="form-control" name="amount">
                        </div>
                    </div>
                    <div class="col-md-12">
                    <div class="form-group ">
                        <label for="contact_name" class="col-sm-3 col-form-label">Date</label>

                     <input type="text" class="form-control" id="date" placeholder="Enter the date" name="date" value="{{ Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">

                    </div>
                </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Internal Remarks</label>
                           <textarea class="form-control" rows="5" name="internal_remarks"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Agent Remarks</label>
                           <textarea class="form-control"  rows="5" name="external_remarks" id="external_remarks"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success btn-sm">Send</button>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $('#additional_service_id').change(() => {
       let text =  $("#additional_service_id option:selected").text();
       $('#external_remarks').val(text);

    })
</script>
@endsection
