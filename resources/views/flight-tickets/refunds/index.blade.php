@extends('layouts.app')
@section('title','Refunds')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Refunds</h4>
                        <p class="card-description">Refunded Tickets in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </div>

                <form class="forms-sample row mb-1" method="GET" action="">
                    <div class="col-md-2">
                        <select name="agent_id" id="agent_id" class="form-control form-control-sm destination select2">
                            <option value="">Select Agent</option>
                            @foreach ($agents as $key => $val)
                                <option value="{{ $val->id }}" @if ($val->id == request()->query('agent_id')) selected @endif>{{ $val->code }}
                                    {{ $val->company_name }} {{ $val->phone }} BL={{ $val->opening_balance }}
                                    CR={{ $val->credit_balance }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="destination_id" id="destination_id" class="form-control form-control-sm destination select2">
                            <option value="">Select Destination</option>
                            @foreach ($destinations as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('destination_id')) selected @endif>{{ ucwords($value->name) }}
                                    {{ $value->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="bill_no" placeholder="Enter the Bill No" value="{{ request()->query('bill_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date" autocomplete="off" placeholder="Enter the Travel Date" value="{{ request()->query('travel_date') }}">
                    </div>


                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $value }}" @if ($value==request()->query('airline')) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                    </div>
                    <div class="col-md-2 mt-1">
                        <input type="hidden" name="from" id="from">
                        <input type="hidden" name="to" id="to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Refund Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
                    </div>
                    <div class="col-md-2 mt-1">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>
                </form>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="javascript:void(0)" class="btn btn-info btn-sm" id="viewAnchor">View</a>
                    @can('refunds update')
                    <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="editAnchor">Edit</a>
                    @endcan
                    @can('refunds delete')
                    <form action="javascript:void(0)" method="post" id="deleteForm">
                        <input class="btn btn-outline-danger btn-sm btnDelete" type="submit" value="Delete" />
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    @endcan
                </div>
                <div class="row mt-3">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered  table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Name</th>
                                <th>Sector</th>
                                <th>Airline</th>
                                <th>Travel Date</th>
                                <th>Bill No</th>

                                <th> PNR No</th>
                                <th>PAX</th>
                                <th>Infant</th>
                                <th>Fare</th>
                                <th>Charge</th>
                                <th> Refund PP</th>
                                <th> Total Refund </th>

                                <th>Refund Date & Time</th>
                                <th>User</th>
                                <th>Remarks</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_pax_cost = 0;
                                $total_pax = 0;
                            @endphp
                            @foreach ($datas as $key => $value)
                                <tr  @if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger
                                     @elseif($value->namelist_status == 4)
                                         table-primary
                                     @elseif($value->namelist_status == 5)
                                         table-secondary
                                @endif">
                                    <td> <input type="radio" name="checked" value="{{ $value->id }}" autocomplete="off"></td>
                                    <td>{{ $value->agent->company_name }}</td>
                                    <td> @if ($value->bookTicket) {{ $value->bookTicket->destination }} @endif</td>
                                    <td> @if ($value->bookTicket) {{ $value->bookTicket->airline }} @endif</td>
                                    <td> @if ($value->bookTicket) {{ $value->bookTicket->travel_date->format('d-M-Y') }} @endif</td>

                                    <td> @if ($value->bookTicket) {{ $value->bookTicket->bill_no }} @else @endif </td>
                                    <td>@if ($value->bookTicket) {{ $value->bookTicket->pnr }} @endif</td>
                                    <td>{{ $value->adult + $value->child }}</td>
                                    <td>{{ $value->infant }}</td>
                                    <td> @if ($value->bookTicket) {{ $value->bookTicket->pax_price }} @else @endif </td>

                                    @php
                                        $total_pax = $total_pax + $value->pax;
                                        $total_pax_cost = $total_pax_cost + $value->pax_cost;
                                    @endphp
                                    <td>{{ $value->pax_cost }}</td>
                                    <td> @if ($value->bookTicket){{ $value->bookTicket->pax_price - $value->pax_cost }} @endif</td>
                                    <td>@if ($value->bookTicket) {{ $value->total_refund }}@endif</td>
                                    <td>{{ $value->created_at->format('d-m-y H:i:s') }}</td>
                                    <td>{{ $value->owner->first_name }}</td>
                                    <td>{{ $value->remarks }}</td>


                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                        <div>
                            {{ $datas->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({

            });
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            @if(request()->query('from'))
                $('#dates').daterangepicker({
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                let from = '{!! request()->query('from') !!}';
                let to = '{!! request()->query('to') !!}';
                $('#from').val(from);
                $('#to').val(to);
                @else
                    $('#dates').daterangepicker({
                    startDate: moment(),
                    endDate: moment(),
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                $('#dates').val('Booking Date Range')
                
            @endif

            $('#dates').on('apply.daterangepicker', function(ev, picker) {
                let from = picker.startDate.format('DD-MM-YYYY');
                let to = picker.endDate.format('DD-MM-YYYY');
                // Update hidden fields
                $('#from').val(from);
                $('#to').val(to);
            });
            $('.btnDelete').click((e) => {
                let resp = confirm("Are you sure you want to delete the Refund Ticket ?")
                if (!resp) {
                    e.preventDefault();
                }
            })
            $("input[name='checked']").click(function(e) {
                let id = $.trim(e.target.value);
                $('#viewAnchor').attr('href', '/flight-tickets/refunds/' + id);
                $('#editAnchor').attr('href', '/flight-tickets/refunds/' + id + '/edit');
                $('#deleteForm').attr('action', '/flight-tickets/refunds/' + id);
            });
        });
    </script>
@endsection
