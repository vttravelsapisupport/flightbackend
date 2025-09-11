@extends('layouts.app')
@section('title','Deposit Requests')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
                        <h4 class="card-title text-uppercase">Deposit Requests</h4>
                        <p class="card-description">Deposit Requests in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
                <form method="GET" action="">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2 ">
                                <option value="">Select Agent</option>
                                @foreach ($agents as $key => $val)
                                    <option value="{{ $val->id }}" @if ($val->id == request()->query('agent_id')) selected @endif>{{ $val->company_name }}
                                        {{ $val->code }} BL- {{ $val->opening_balance }}
                                        CR={{ $val->credit_balance }}

                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control form-control-sm " name="amount" id="amount" autocomplete="off" placeholder="Amount" value="{{ request()->query('amount') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="start_date" id="start_date">
                            <input type="hidden" name="end_date" id="end_date">
                            <input type="text" class="form-control form-control-sm" id="dates" placeholder="" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                        </div>
                        <!-- <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm datepicker" name="deposit_from" id="deposit_from" autocomplete="off" placeholder="Enter the deposit Date From" value="{{ request()->query('deposit_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm datepicker" name="deposit_to" id="deposit_to" autocomplete="off" placeholder="Enter the deposit Date To" value="{{ request()->query('deposit_to') }}">
                        </div> -->
                        <div class="col-md-2">
                            <select name="bank_id" class="form-control form-control-sm">
                                <option value="">Select Bank</option>
                                <option value="icici" @if(request()->query('bank_id')  == 'icici') selected @endif>ICICI</option>
                                <option value="state bank of india"  @if(request()->query('bank_id')  == 'state bank of india') selected @endif>State Bank of India</option>
                                <option value="Canara Bank"  @if(request()->query('bank_id')  == 'Canara Bank') selected @endif>Canara Bank</option>
                                <option value="973309600@okbizaxis"  @if(request()->query('bank_id')  == '973309600@okbizaxis') selected @endif>973309600@okbizaxis</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Select Status</option>
                                <option value="1" @if(request()->query('status')  == 1) selected @endif>Pending</option>
                                <option value="3" @if(request()->query('status')  == 3) selected @endif>Rejected</option>
                                <option value="2" @if(request()->query('status')  == 2) selected @endif>Approved</option>

                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm" name="search"> Search</button>
                        </div>
                    </div>
                </form>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Agent</th>
                            <th>Agent Code</th>
                            <th>Deposit Type</th>
                            <th>Amount</th>
                            <th>Ref. No</th>
                            <th>Date</th>
                            <th>Account Holder's Name</th>
                            <th>Mobile Number</th>
                            <th>Bank</th>
                            <th>Remarks</th>
                            <th>Updated Date</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @if ($datas->count() > 0)
                            @foreach ($datas as $key => $value)
                                <tr>
                                    <td>{{ 1 + $key }} </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('deposit-request approve')
                                                <button value="{{ $value->id }}" type="button" class="btn btn-success btn-sm approveBtn" @if ($value->status == 2 || $value->status != 1)
                                                    disabled
                                                    @endif
                                                >
                                                    <i class="mdi mdi-check menu-icon"></i>
                                                </button>
                                            @endcan
                                            @can('deposit-request reject')
                                                <button value="{{ $value->id }}" type="button" class="btn btn-danger btn-sm rejectBtn" @if ($value->status == 2 ||
                                        $value->status != 1)
                                                    disabled
                                                    @endif>
                                                    <i class="mdi mdi-trash-can menu-icon"></i>
                                                </button>
                                            @endcan

                                            <a href="{{ $value->files }}" target="receipt_download" download class="btn btn-warning btn-sm" id="refundAnchor">
                                                <i class="mdi mdi-download menu-icon"></i>
                                            </a>

                                        </div>
                                    </td>
                                    <td>

                                        @if ($value->status == 1)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($value->status == 2)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif

                                    </td>
                                    <td><a href="{{ route('agents.show', $value->agent->id) }}">{{ $value->agent->company_name }}</a>
                                    </td>
                                    <td>{{ $value->agent->code }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>{{ $value->amount }}</td>
                                    <td style="text-overflow: ellipsis; max-width: 150px; overflow: hidden;">{{ $value->ref_number }}</td>
                                    <td>{{ $value->date->format('d-m-Y') }}</td>
                                    <td>{{ $value->account }}</td>
                                    <td>{{ $value->phone }}</td>
                                    <td>{{ $value->bank }}</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->created_at }}</td>

                                </tr>

                            @endforeach
                        @else
                        @endif
                        </tbody>
                    </table>
                    <div class="mt-3">
                        @if ($datas->count() > 0)
                            {{ $datas->appends(request()->except('page'))->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('.approveBtn').click(function(e) {
            e.preventDefault();
            let id = $(this).val();
            let resp = confirm("Are you sure you want Approve ?");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (resp) {
                $.ajax({
                    type: 'PUT',
                    url: '/accounts/deposit-requests/' + id,
                    data: {
                        status: 2
                    },
                    success: function(resp) {
                        if (resp.success) {
                            alert(resp.message);
                            location.reload();
                        }else{
                            alert(resp.message);
                        }
                    }
                })
            }

        })
        $('.rejectBtn').click(function(e) {
            e.preventDefault();
            let id = $(this).val();
            let resp = confirm("Are you sure you want Reject ?");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (resp) {
                $.ajax({
                    type: 'PUT',
                    url: '/accounts/deposit-requests/' + id,
                    data: {
                        status: 3
                    },
                    success: function(resp) {
                        console.log(resp);
                        if (resp.success) {
                            location.reload();
                        }
                    }
                })
            }

        })

        // $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        // $('#deposit_from').change(function() {
        //     let from = $('#deposit_from').val();
        //     $('#deposit_to').val(from);
        // })

        $('.select2').select2({});

        @if(request()->query('start_date'))
            $('#dates').daterangepicker({
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            let start_date = '{!! request()->query('start_date') !!}';
            let end_date = '{!! request()->query('end_date') !!}';
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
            @else
                $('#dates').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            $('#dates').val('Deposit Date Range')

        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let start_date = picker.startDate.format('DD-MM-YYYY');
            let end_date = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
        });

        // $('#start_date').change(function() {
        //     let start_date = $('#start_date').val();
        //     let start_date_day = start_date.split('-')[0];
        //     let start_date_month =  start_date.split('-')[1];
        //     let start_date_year = start_date.split('-')[2];

        //     let endDate = $('#end_date');
        //     endDate.datepicker('destroy');
        //     endDate.datepicker({format:'dd-mm-yyyy', startDate:new Date(start_date_month+'-'+start_date_day+'-'+start_date_year) });
        //     endDate.val(start_date);
        //     endDate.attr("required", "true");
        // })

        $("#agent-select2").select2({
            allowClear: false,
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
    </script>
@endsection
