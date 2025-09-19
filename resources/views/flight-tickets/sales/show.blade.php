@extends('layouts.app')
@section('title','Sales')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 btn-group float-right">
                    <a href="{{ url('/flight-tickets/sales/print/'.$data->id) }}" target="_blank" class="btn-secondary btn-sm py-2">PRINT <i class="mdi mdi-printer"></i></a>
                    <a href="{{ url('/flight-tickets/sales/pdf/'.$data->id) }}" target="_blank" class="btn-success btn-sm py-2" style="background-color:#8C0303">PDF <i class="mdi mdi-file-pdf"></i></a>
                    <div class="btn-group">
                        <button class="btn-sm btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            SEND TICKET <i class="mdi mdi-send"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ url('/flight-tickets/'.$data->id.'/whatsapp') }}">Via Whats App</a>
                            <a class="dropdown-item" href="#" style="pointer-events: none">Via Sms</a>
                            <a class="dropdown-item" href="{{ url('/flight-tickets/'.$data->id.'/email') }}">Via Email</a>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn-sm btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ACTION <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" style="margin-right: 2rem;">
                            <a class="dropdown-item" href="{{ url('/flight-tickets/refund-ticket/create?book_ticket_id='.$data->id)}}">Refund</a>
                            <a class="dropdown-item" href="{{ url('/flight-tickets/sales/'.$data->id.'/services')}}">Services</a>
                            <a class="dropdown-item" href="{{ url('flight-tickets/sales/initimation/'.$data->id)}}">Intimation</a>
                            <a class="dropdown-item" href="{{ url('/flight-tickets/refund-booking/seatLive?book_ticket_id='.$data->id.'&seat_live=true')}}">Seat Live Request</a>
                            <!-- <a class="dropdown-item" href="{{ url('flight-tickets/pnr-name-list/'.$data->purchase_entry_id)}}">Namelist</a> -->
                        </div>
                    </div>
                </div>
                <table class="table table-sm">
                    <thead>
                    <tr class="thead-dark">
                        <th>Booked On</th>
                        <th>Reference No.</th>
                        <th>Agent</th>
                        <th>Price</th>
                    </tr>
                    <tr>
                       
                        <td>{{ $data->created_at}}</td>
                        <td>{{ $data->bill_no }}</td>
                        <td> {{ $data->company_name }}({{ $data->code }})</td>
                        <td>
                            Rs. {{ ($data->pax_price * $data->adults) + ($data->child_charge * $data->child) + ($data->infant_charge * $data->infants)}}
                        </td>
                    </tr>
                    </thead>
                </table>
                <table class="table table-sm">
                    <thead>
                    <tr class="thead-dark">
                        <th>PNR NO</th>
                        <th>Trip Type</th>
                        <th>Flight</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                    </tr>
                    <tr>
                        <td>
                           
                               {{  $data->pnr,true }}
                          </td>
                        <td>
                            @if($data->trip_type == 1)
                            <span class="badge badge-info">One Way</span>
                            @else
                            <span class="badge badge-warning">Round Trip</span>
                            @endif
                        </td>
                        <td> {{ $data->airline }} </td>
                        <td>
                            {{ $data->departureDate}}  
                           
                        </td>
                        <td>
                            {{ $data->arrivalDate }}  
                           
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
                        <th>Type</th>
                        <th>Travelling With</th>
                        <th>DOB</th>
                        <th>PNR</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($book_ticket_details as $key => $val)
                        <tr @if($val->is_refund == 1) class="bg-red" @endif>
                            <td>
                                &nbsp; {{ 1 +$key }}.
                            </td>
                            <td>
                                {{ $val->title }}
                            </td>
                            <td>
                                {{ $val->first_name }}
                            </td>
                            <td>
                                {{ $val->last_name }}
                            </td>
                            <td>
                                @if($val->type == 1)
                                    Adult
                                @elseif($val->type == 2)
                                    Child
                                @elseif($val->type == 3)
                                    Infants
                                @endif
                            </td>
                            <td>{{ $val->travelling_with}}</td>
                            <td> @if($val->dob)
                                    {{ $val->dob->format('d-M-y')}}
                                @else
                                @endif
                            </td>
                            <td> {{ $val->pnr}}
                            </td>

                        </tr>
                    </tbody>
                    @endforeach
                </table>
                <p>
                   <h6>Remark: </h6> {{ $data->remark }}
                </p>


                <h6>Ticket Services</h6>
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Service Name</th>
                        <th>Amount</th>
                        <th>Internal Remark</th>
                        <th>Agent Remark</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ticketServices as $key => $val)
                        <tr @if($val->isrefund == 1) class="bg-red" @endif>
                            <td>
                                &nbsp; {{ 1 +$key }}.
                            </td>
                            <td>
                                {{ $val->additional_service->name }}
                            </td>
                            <td>
                                {{ $val->amount }}
                            </td>
                            <td>
                                {{ $val->internal_remarks }}
                            </td>
                            <td>
                                {{ $val->external_remarks }}
                            </td>

                        </tr>
                    </tbody>
                    @endforeach
                </table>
                <p>
                    {{ $data->remark }}
                </p>

                <h6>Refund Details :</h6>
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Refund Charges</th>
                            <th>Refund Amount</th>
                            <th>Refund Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>

                        </tr>
                    </tbody>
                </table>

                <hr>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Change History</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>IP</th>
                                    <th>Created At</th>
                                    <th>Old Data</th>
                                    <th>New Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($book_ticket_details as $summary)
                                        @foreach($summary->audits as $key => $val)
                                            <tr>
                                                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                                <td>{{ optional($val->user)->first_name ?? '' }} {{ optional($val->user)->last_name ?? '' }}</td>
                                                <td>{{ $val->ip_address ?? '-' }}</td>
                                                <td>{{ $val->created_at->format('d-M-Y H:i:s') }}</td>
                                                <td>
                                                    @foreach($val->old_values as $field => $old)
                                                        @if($field == 'is_refund')
                                                            {{ $field }} : {{ $old ? 'Refunded' : 'Not Refunded' }} 
                                                        @elseif($field == 'status')
                                                            {{ $field }} :
                                                            @if($old == 1) Active
                                                            @elseif($old == 2) Cancelled
                                                            @else {{ $old }}
                                                            @endif 
                                                        @else
                                                            {{ $field }} : {{ $old }} 
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($val->new_values as $field2 => $new)
                                                        @if($field2 == 'is_refund')
                                                            {{ $field2 }} : {{ $new ? 'Refunded' : 'Not Refunded' }}
                                                        @elseif($field2 == 'status')
                                                            {{ $field2 }} :
                                                            @if($new == 1) Active
                                                            @elseif($new == 2) Cancelled
                                                            @else {{ $new }}
                                                            @endif 
                                                        @else
                                                            {{ $field2 }} : {{ $new }} 
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                     </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
