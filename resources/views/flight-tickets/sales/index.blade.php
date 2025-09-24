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
                        <h4 class="card-title text-uppercase">Sales</h4>
                        <p class="card-description">Sold Tickets in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
                <form class="forms-sample row" method="GET" action="">
                <div class="col-md-4">
                    <select name="agency_id" id="agent-select2" class="form-control select2">
                            @if($agent)
                                <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                            <option value="">Select Agent</option>
                    </select>
                </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control" name="bill_no" placeholder="Enter the Bill No" autocomplete="off" value="{{ request('bill_no') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm datepicker" name="travel_date" placeholder="Enter the Travel Date" autocomplete="off" value="{{ request('travel_date') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" autocomplete="off" value="{{ request('pnr_no') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="src" placeholder="Enter the Origin" autocomplete="off" value="{{ request('src') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="dest" placeholder="Enter the Destination" autocomplete="off" value="{{ request('dest') }}">
                    </div>



                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>

                </form>
                <div class="mt-2">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="javascript:void(0)" class="btn btn-info btn-sm" id="viewAnchor">View</a>

                        @can('sold_ticket edit')
                            <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="editAnchor">Edit</a>
                        @endcan
                    </div>


                </div>
                <div class="row mt-3">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Agent</th>
                                    <th>Bill No</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>PNR No.</th>
                                    <th>Trip Type</th>
                                    <th>Pax</th>
                                    <th>Price</th>
                                    <th>Infant</th>
                                    <th>Inf. Price</th>
                                    <th>Travel Date/Time</th>
                                    <th>Airline/Flight No</th>
                                    <th>Booking Date</th>
                                    <th>Booking Source</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if($data->isEmpty())
                                    <tr>
                                        <th colspan="18" class="text-center">No Record Found</th>
                                    </tr>

                                @else
                                    @foreach($data as $item)
                                        <tr>
                                            <td>
                                                <input type="radio" name="checked" value="{{ $item->id }}" autocomplete="off">
                                            </td>
                                            <td>{{ $item->company_name  }}({{ $item->code}})</td>
                                            <td>{{ $item->bill_no  }}</td>
                                            <td>{{ $item->src  }} </td>
                                            <td>{{ $item->dest  }}</td>
                                            <td>{{  $item->pnr }}</td>
                                            <td>{{ $item->trip_type == 1 ? 'One Way' : 'Round Trip' }}</td>
                                            <td>{{ (int)($item->adults ?? 0) + (int)($item->infants ?? 0)   + (int) ($item->child ?? 0) }} </td>
                                            <td>{{ $item->total_amount  }}</td>
                                            <td>{{ $item->infants  }}</td>
                                            <td>{{ $item->infant_charge  }}</td>
                                            <td>
                                                @if($item->departureDate)
                                                    {{ \Carbon\Carbon::parse($item->departureDate)->format('d-m-Y H:i ') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $item->airline }}</td>

                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                {{ \App\Enums\BookTicketBookingSourceEnum::tryFrom($item->booking_source)->getDisplayName() }}
                                            </td>
                                            <td>{{ \App\Enums\BookTicketStatusEnum::tryFrom($item->status)->getDisplayName()  }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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


