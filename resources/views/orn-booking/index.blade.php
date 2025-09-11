@extends('layouts.app')
@section('title','Sales')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
          rel="stylesheet" />
    <style>
        .select2-results__option {
            padding: 0px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">API SALES</h4>
                        <p class="card-description">API TICKET SOLD IN ORN.</p>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
                <form class="forms-sample row" method="GET" action="">
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
                        <select name="agent_id" id="agent-select2" class="form-control form-control-sm destination select2">
                            <option value="">Select Agents</option>
                            @if($agent)
                                <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control" name="bill_no" placeholder="Enter the ORN No" autocomplete="off" value="{{ request()->query('bill_no') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date" placeholder="Enter the Travel Date" autocomplete="off" value="{{ request()->query('travel_date') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $key }}" @if ($key==request()->query('airline')) selected @endif>{{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="owner_id" id="owner_id" class="form-control form-control-sm  select2">
                            <option value="">Select Owner</option>
                            @foreach ($owners as $id => $name)
                                <option value="{{ $id }}" @if ($id == request()->query('owner_id')) selected @endif>{{ 'SID'.$id .' - '. ucwords($name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="supplier_id" id="supplier_id" class="form-control form-control-sm  select2">
                            <option value="">Select Third Party Owner</option>
                            @foreach ($suppliers as  $id => $name)
                                <option value="{{$id }}" @if ($id== request()->query('supplier_id')) selected @endif>{{ 'SID'.$id .' - '.ucwords($name) }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" autocomplete="off" value="{{ request()->query('pnr_no') }}">
                    </div>




                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>

                </form>
              
                <div class="row mt-3">

                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark ">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Bill No</th>
                                <th>Destination</th>
                                <th>PNR No.</th>
                                <th>Trip Type</th>
                                <th>Pax</th>
                                <th>Price</th>
                                <th>Infant</th>
                                <th>Inf. Price</th>
                                <th>Travel Date</th>
                                <th>DEPT</th>
                                <th>Airline</th>
                                <th>Flight No</th>
                                <th>Vendor</th>
                                <th>Created At</th>
                                <th>User</th>
                                <th>Remark</th>
                                <th>Source</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if($data->count() > 0 )

                            @foreach ($data as $key => $value)
                                @if ($value->p_deleted_at == NULL)
                                    <tr class="@if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger @endif">
                                        <td>
                                            <input type="radio" name="checked" value="{{ $value->id }}" autocomplete="off">
                                        </td>
                                        <td>{{ $value->company_name }}</td>
                                        <td>{{ ucwords($value->bill_no) }}
                                            @if($value->status == 1)
                                                <span class="badge badge-success">Live</span>
                                            @endif
                                            @if($value->is_refundable == 1)
                                                <span class="badge badge-success">RFD</span>
                                            @endif

                                            @if($value->status == 2)
                                                <span class="badge badge-warning">
                                        @if($value->seat_live_count)
                                            Seat Live : {{$value->seat_live_count}}
                                        @endif
                                    </span>
                                            @endif

                                            @if($value->status == 3)
                                                <span class="badge badge-info">Part XXLD</span>
                                            @endif

                                            @if($value->status == 4)
                                                <span class="badge badge-danger">XXLD</span>
                                            @endif

                                        </td>
                                        <td>{{ $value->destination_name }}</td>

                                        <td>
                                            {{ $value->pnr }}
                                           
                                        </td>
                                        <td  class="">
                                            @if($value->trip_type == 1)
                                            <span class="badge badge-info">One Way</span>
                                            @else
                                            <span class="badge badge-warning">Round Trip</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->adults + $value->child }}</td>
                                        <td>{{ $value->pax_price }}</td>


                                        <td>{{ $value->infants }}</td>
                                        <td>@if($value->infants != 0 ){{ $value->infant_charge}} @else 0 @endif</td>
                                        <td>{{ $value->travel_date->format('d-M-Y') }}</td>
                                        <td>{{ $value->travel_time }}</td>
                                        <td>{{ $value->airline }}</td>
                                        <td>{{ $value->flight_no }}</td>
                                        <td
                                            @if($value->owner_type == 1) class="bg-warning font-weight-bold" @endif title="@if($value->owner_type == 1)Third Party Vendor  @endif"
                                            @if($value->owner_type == 2) class="bg-info font-weight-bold" @endif title="@if($value->owner_type == 1)API Vendors  @endif"
                                        > {{ ucwords($value->owner_name) }}</td>
                                        <td>{{ $value->created_at->format('d-M-Y h:i:s') }}</td>
                                        <td>{{ $value->user_name }}</td>
                                        <td>{{ $value->remark }}</td>
                                        <td
                                            @if($value->booking_source == 'api') class="bg-info font-weight-bold" @endif
                                        @if($value->booking_source == null) class="bg-warning font-weight-bold" @endif
                                        >
                                            {{ $value->booking_source ? strtoupper($value->booking_source) : 'Admin'}}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center bg-red text-white">PURCHASE TICKET ID
                                            ({{ $value->purchase_entry_id }}) DELETED ({{ $value->bill_no }})</td>
                                    </tr>
                                @endif
                            @endforeach
                           @else
                            <tr>
                                <th colspan="18" class="text-center"> No Record Found</th>
                            </tr>
                           @endif


                            </tbody>

                        </table>
                        <div>
                            {{ $data->appends(request()->input())->links() }}
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
    <script>
        $(document).ready(function() {

            $('.select2').select2({

            });
            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});


            $("input[name='checked']").click(function(e) {
                let id = $.trim(e.target.value);

                $('#IntimateAnchor').attr('href', '/flight-tickets/sales/initimation/' + id);
                $('#viewAnchor').attr('href', '/flight-tickets/sales/' + id);
                $('#editAnchor').attr('href', '/flight-tickets/sales/' + id + '/edit');
                $('#refundAnchor').attr('href', '/flight-tickets/refund-ticket/create?book_ticket_id=' + id);
                $('#servicesButton').attr('href', '/flight-tickets/sales/' + id + '/services');
                $('#btnDelete').attr('href', '/flight-tickets/sales/delete/' + id);
            });

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
    </script>
@endsection


