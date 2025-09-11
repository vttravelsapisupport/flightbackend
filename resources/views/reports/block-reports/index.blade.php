@extends('layouts.app')
@section('title','Block Reports')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
          rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Block Reports</h4>
                        <p class="card-description">Blocked Reports in the Appication.</p>
                    </div>

                </div>
                <form class="forms-sample row" method="GET" action="">
                    <div class="col-md-2">
                        {{-- <select name="agent_id" id="agent_id" class="form-control form-control-sm ">
                            <option value="">Select Agent</option>
                            @foreach ($agents-distributors as $key => $value)
                                <option value="{{ $key }}" @if ($key == request()->query('agent_id')) selected @endif>{{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select> --}}
                        <select name="agent_id" id="agent_id" class="form-control   form-control-sm select2">
                            <option value="">Select Agent</option>
                            @foreach ($agents as $key => $val)
                                <option value="{{ $val->id }}" @if ($val->id == request()->query('agent_id')) selected @endif>{{ $val->code }}
                                    {{ $val->company_name }} {{ $val->phone }} BL={{ $val->opening_balance }}
                                    CR={{ $val->credit_balance }}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="destination_id" id="destination_id"
                                class="form-control form-control-sm destination select2">
                            <option value="">Select Destination</option>
                            @foreach ($destinations as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('destination_id')) selected @endif>{{ ucwords($value->name) }}
                                    {{ $value->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="travel_date_from" id="travel_date_from">
                        <input type="hidden" name="travel_date_to" id="travel_date_to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('travel_date_from') }} - {{ request()->query('travel_date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $key }}" @if ($key == request()->query('airline')) selected @endif>{{ ucwords($value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="pnr_no" placeholder="Enter the PNR No"
                               value="{{ request()->query('pnr_no') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>

                </form>
                <div class="row mt-3">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th class="sortStyle ascStyle">A/C Name</th>

                                <th class="sortStyle ascStyle">PNR</th>
                                <th class="sortStyle ascStyle">Destination</th>
                                <th class="sortStyle ascStyle">Travel Date</th>
                                <th class="sortStyle ascStyle">Airline</th>
                                <th class="sortStyle ascStyle">Quantity</th>
                                <th class="sortStyle ascStyle">Remark</th>
                                <th class="sortStyle ascStyle">Block Date & time
                                <th class="sortStyle ascStyle">User
                                </th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ 1 + $key }}</td>
                                    <td>{{ $value->agency_name }}</td>
                                    <td>{{ $value->pnr }}</td>
                                    <td>{{ $value->destination_name }}</td>
                                    <td>{{ Carbon\Carbon::parse($value->travel_date)->format('d-m-Y') }}</td>
                                    <td>{{ $value->airline_name }}</td>
                                    <td>{{ $value->quantity }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($value->remarks) }}</td>
                                    <td>{{ $value->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('block-release')
                                                <button type="button"
                                                        id="release_modal_button"
                                                        class="btn btn-primary" data-toggle="modal" data-target="#release_modal"
                                                        data-id="{{ $value->book_ticket_id }}"
                                                        data-qty="{{ $value->quantity }}"
                                                        data-rmk="{{ $value->remarks }}">
                                                    Release
                                                </button>
                                            @endcan
                                            @can('block-booking')
                                                <a href="{{ url('flight-tickets/refund-ticket/create?book_ticket_id='.$value->book_ticket_id) }}"
                                                   class="btn btn-outline-success btn-sm">Book</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="mt-2">
                        @if ($data->count() > 0)
                            {{ $data->appends(request()->input())->links() }}
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Release Modal -->
    <div class="modal" id="release_modal" tabindex="-1" role="dialog" aria-labelledby="release_modal_label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="release_modal_label">Release a blocked report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/flight-tickets/blocks/release" id="release-ticket">
                        @csrf
                        <div class="form-group">
                            <label for="release_quantity">Quantity </label>
                            <input type="tel" class="form-control" id="release_quantity" name="release_quantity" maxlength="4" required>
                            <small class="form-text text-muted">Enter the quantity to release</small>
                        </div>
                        <div class="form-group">
                            <label for="release_remarks">Remarks</label>
                            <textarea class="form-control" id="release_remarks" name="release_remarks" rows="10" required></textarea>
                        </div>
                        <input type="hidden" name="release_id">
                        <button type="submit" class="btn btn-primary" id="release-submit" style="width: 100%;">Release</button>
                    </form>
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

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            @if(request()->query('travel_date_from'))
                $('#dates').daterangepicker({
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                let travel_date_from = '{!! request()->query('travel_date_from') !!}';
                let travel_date_to = '{!! request()->query('travel_date_to') !!}';
                $('#travel_date_from').val(travel_date_from);
                $('#travel_date_to').val(travel_date_to);
                @else
                    $('#dates').daterangepicker({
                    startDate: moment(),
                    endDate: moment(),
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                $('#dates').val('Travel Date Range')

            @endif

            $('#dates').on('apply.daterangepicker', function(ev, picker) {
                let travel_date_from = picker.startDate.format('DD-MM-YYYY');
                let travel_date_to = picker.endDate.format('DD-MM-YYYY');
                // Update hidden fields
                $('#travel_date_from').val(travel_date_from);
                $('#travel_date_to').val(travel_date_to);
            });

            $('.select2').select2({});

        });
    </script>
    <script>
        $('#release_modal').on('show.bs.modal', function(e) {
            const id = $(e.relatedTarget).data('id');
            const qty = $(e.relatedTarget).data('qty');
            const rmk = $(e.relatedTarget).data('rmk');
            $(e.currentTarget).find('input[name="release_id"]').val(id);
            $(e.currentTarget).find('input[name="release_quantity"]').val(qty);
            $(e.currentTarget).find('textarea[name="release_remarks"]').val(rmk);
        });


        $('#release-ticket').submit(function(){
            // Disable the submit button
            $('#release-submit').attr('disabled', true);

            return true;
        });
    </script>
@endsection
