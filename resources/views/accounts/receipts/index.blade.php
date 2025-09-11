@extends('layouts.app')
@section('title','Receipts')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
                        <h4 class="card-title text-uppercase">Agent Receipts</h4>
                        <p class="card-description">All Receipts in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('agent-receipts.create') }}" class="btn btn-sm btn-primary">New Agent Receipt</a>
                    </div>
                </div>
                <form action="">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2">
                                <option value="">Select Agent</option>
                                @if($agent)
                                    <option value="{{$agent->id}}" selected >{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="start_date" id="start_date">
                            <input type="hidden" name="end_date" id="end_date">
                            <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm">Search</button>
                        </div>
                    </div>

                </form>

                <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    {{-- <th>Ref. No</th> --}}
                                    <th> Date</th>
                                    <th>Agent code & name </th>
                                    <th>Amount</th>
                                    <th>Payment Mode</th>

                                    <th>Bank</th>
                                    <th>Remarks</th>
                                    <th>Created By</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $val)
                                        <tr>
                                            <td>{{ 1 + $key }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <button value="{{ $val->id }}" type="button" class="btn btn-success btn-sm approveBtn" @if ($val->status == 2 || $val->status == 3)
                                                        disabled
                                                        @endif
                                                    >
                                                        <i class="mdi mdi-check menu-icon"></i>
                                                    </button>
                                                    <button value="{{ $val->id }}" data-agent_id="{{$val->agent_id}}" type="button" class="btn btn-danger btn-sm rejectBtn" @if ($val->status == 3 ||
                                            $val->status == 2)
                                                        disabled
                                                        @endif>
                                                        <i class="mdi mdi-trash-can menu-icon"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <th>
                                                @if ($val->status == 1 || $val->status == null)
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($val->status == 2)
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </th>
                                            <td>{{ $val->created_at->format('d-m-Y') }}
                                                {{ $val->created_at->format('h:i:a') }}
                                            </td>
                                            <td>
                                            {{ $val->date->format('d-m-Y') }}

                                            </td>
                                            {{-- <td>RCPT-{{ $val->id }}</td> --}}
                                            <td>{{ $val->agentDetails->code }} -
                                                {{ $val->agentDetails->company_name }}</td>
                                            @php
                                                $total += $val->amount;
                                            @endphp
                                            <td> @money($val->amount)</td>
                                            <td>
                                                @if ($val->payment_mode == 1)
                                                    Cash
                                                @elseif($val->payment_mode == 2)
                                                    Online Transfer
                                                @elseif($val->payment_mode == 3)
                                                    Cash Deposit
                                                @elseif($val->payment_mode == 4)
                                                    Agent Incentive
                                                @elseif($val->payment_mode == 5)
                                                    Discount
                                                @endif
                                            </td>
                                            <td> @if ($val->bank_id){{ $val->bankDetails->name }} @else @endif</td>
                                            <td>{{ $val->remarks }}</td>
                                            <td>@if($val->owner_id){{ $val->owner->first_name }} {{ $val->owner->last_name }} @endif</td>
                                            <td>

                                                <a href="{{ route('agent-receipts.show', $val->id) }}"
                                                    class="btn btn-sm btn-success">View</a>
                                                <!-- <a href="{{ route('agent-receipts.edit', $val->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="14" class="text-center">No Result Found</th>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                            @if ($datas->count() > 0)
                                <tr>

                                    <td colspan="5">Total</td>
                                    <td colspan="6"> @money($total)</td>
                                </tr>
                             @endif
                            </tfoot>

                        </table>
                        @if ($datas->count() > 0)
                        {{ $datas->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-2">Import Receipts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('receipts/exports') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label for="" class="col-form-label col-md-3">Select Source</label>
                                    <div class="col-md-9">
                                        <input type="file" name="excel" class="form-control-file">
                                        <small><a href="{{ asset('excel/receiptsimport.csv') }}">
                                                <i class="mdi mdi-download "></i>
                                                Download Excel Format
                                            </a></small>
                                        <br>
                                        <small>
                                            <strong> Note: Delete the first row from Excel format excel sheet </strong>
                                        </small>


                                    </div>
                                    <table class="table">
                                        <tr>
                                            <th>Payment Mode</th>
                                        </tr>
                                        <tr>
                                            <th>Details</th>
                                            <th>Code</th>
                                        </tr>

                                        <tr>
                                            <td>Cash</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>Online Transfer</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>Cash Deposit</td>
                                            <td>3</td>
                                        </tr>
                                    </table>
                                    <table class="table">
                                        <tr>
                                            <th>Bank Details</th>
                                        </tr>
                                        <tr>
                                            <th>Details</th>
                                            <th>Code</th>
                                        </tr>

                                        <tr>
                                            <td>Simply Flysmart Private Limited(ICICI Bank)</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>FLY SMART (State Bank Of India)</td>
                                            <td>2</td>
                                        </tr>
                                        <tr>
                                            <td>9733096000@okbizaxis</td>
                                            <td>3</td>
                                        </tr>
                                        <tr>
                                            <td>9755031000@okbizaxis</td>
                                            <td>4</td>
                                        </tr>
                                        <tr>
                                            <td>Cananara Bank</td>
                                            <td>5</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm">Upload</button>
                        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    let start_date =   $('#start_date').val();
    let end_date   =   $('#end_date').val();
    if(!start_date && !end_date ){
        let today = new Date();
        let date1 = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        $('#end_date').val(date1);

        let today1       = new Date()
        let days         = 86400000
        let sevenDaysAgo = new Date(today1 - (30*days))
        let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
        $('#start_date').val(date2);
    }

    $(document).ready(function() {
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
        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let start_date = picker.startDate.format('DD-MM-YYYY');
            let end_date = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
        });

        $("#agent-select2").select2({

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
                    type: 'POST',
                    url: '/accounts/receipts/update/' + id,
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
            let agent_id = $(this).data('agent_id');
            let resp = confirm("Are you sure you want Reject ?");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (resp) {
                $.ajax({
                    type: 'POST',
                    url: '/accounts/receipts/update/' + id,
                    data: {
                        status: 3,
                        agent_id : agent_id
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
    });
</script>
@endsection
