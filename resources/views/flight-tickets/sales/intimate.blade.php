@extends('layouts.app')
@section('title', 'Sales')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" />
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
                <h4 class="card-title text-uppercase">Intimate </h4>
                <p class="card-description">
                    Intimate {{ $data->bill_no }}
                </p>
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
                                    @if ($data->agent->company_name)
                                        {{ $data->agent->company_name }}
                                    @else
                                        {{ $data->agent->contact_name }}
                                    @endif
                                </a>
                            </td>
                            <td>
                                Rs. {{ $data->pax_price * $data->adults }}
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
                                {{ $data->destinationDetails->origin->name }} (
                                {{ $data->destinationDetails->origin->code }} ) <br>
                                {{ $data->purchase_entry->travel_date->format('d-m-Y') }}
                                {{ $data->purchase_entry->departure_time }}

                            </td>
                            <td>
                                {{ $data->destinationDetails->destination->name }} (
                                {{ $data->destinationDetails->destination->code }} ) <br>
                                {{ $data->purchase_entry->travel_date->format('d-m-Y') }}
                                {{ $data->purchase_entry->arrival_time }}
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
                        @foreach ($book_ticket_details as $key => $val)
                            <tr @if ($val->is_refund == 1) class="bg-red" @endif>
                                <td>
                                    &nbsp; {{ 1 + $key }}.
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
                            </tr>
                    </tbody>
                    @endforeach
                </table>

                <h6 class="mt-3">Initimations </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Content</th>
                                <th>Agent Remark</th>
                                <th>Internal Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($initimations as $key => $val)
                                <tr>
                                    <td>
                                        &nbsp; {{ 1 + $key }}.
                                    </td>
                                    <td>
                                        {{ $val->subject }}
                                    </td>
                                    <td>
                                        <pre style="background: none !important;">{{ $val->content }}</pre>
                                    </td>
                                    <td>
                                        @foreach ($val->AgentIntimationRemarks as $key1 => $val1)
                                            {{ $val1->remark }} <br> <small>
                                                {{ $val1->created_at->format('d-m-Y h:i:s') }} </small><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($val->InternalIntimationRemarks)
                                            @foreach ($val->InternalIntimationRemarks as $key => $val2)
                                                {{ $val2->remark }} <br> <small>
                                                    {{ $val2->created_at->format('d-m-Y h:i:s') }} </small><br>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-primary addComment"
                                            value="{{ $val->id }}">Remark</button>
                                    </td>

                                </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>

                <h6 class="mt-3 mb-3">
                    Comments
                    <button class="btn btn-sm btn-primary addComment float-right" value="{{ $data->id }}">Remark</button>
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Comments</th>
                                <th>User</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $key => $val)
                                <tr>
                                    <td>&nbsp; {{ 1 + $key }}.
                                    </td>
                                    <td>{{ $val->notes }} </td>
                                    <td>{{ $val->user->first_name }} {{ $val->user->last_name }} </td>
                                    <td>{{ $val->created_at->format('d-m-Y h:i:s') }}</td>
                                </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Remarks 1</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ url('flight-tickets/intimation-remark') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="remark_id" id="remark_id">
                                    <div class=" form-group">
                                        <label for="">Select Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="1">Agent remark</option>
                                            <option value="2">Internal Remark</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Remark</label>
                                        <textarea name="remark" id="" cols="30" rows="5" class="form-control"></textarea>
                                    </div>



                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="commentModel" tabindex="-1" role="dialog" aria-labelledby="commentModelLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Comment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ url('flight-tickets/sales/comment') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="book_ticket_id" id="book_ticket_id">
                                    <div class="form-group">
                                        <label for="">Comment</label>
                                        <textarea name="remark" id="" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <p>
                    <span class="font font-weight-bold">Sales Remark</span>   {{ $data->remark }}

                </p>
                <hr>
                <h6>Send Email to Agent</h6>
                <form action="{{ url('flight-tickets/sales/initimation/' . $id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="">

                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="subject">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Content</label>
                            <textarea class="form-control" name="contents" rows="10"></textarea>
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
        $('.addRemark').click(function(e) {
            let initimationID = e.target.value;
            console.log(initimationID);
            $('#remark_id').val(initimationID);

            $('#exampleModal').modal('show')
        })
        $('.addComment').click(function(e) {
            let initimationID = e.target.value;
            console.log(initimationID);
            $('#book_ticket_id').val(initimationID);

            $('#commentModel').modal('show')
        })

    </script>
@endsection
