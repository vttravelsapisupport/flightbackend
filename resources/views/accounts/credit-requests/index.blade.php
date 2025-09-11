@extends('layouts.app')
@section('title','Credit Requests')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
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
                    <h4 class="card-title text-uppercase">Credit Requests</h4>
                    <p class="card-description">Credit Requests in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-3">

                        <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2 ">
                            <option value="">Select Agent</option>
                            @if($agent)
                                <option value="{{$agent->id}}" selected>{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="text" class="form-control form-control-sm " name="amount" id="amount" autocomplete="off" placeholder="Amount" value="{{ request()->query('amount') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Select Status</option>
                            <option value="1" @if(request()->query('status')  == 1) selected @endif>Pending</option>
                            <option value="2" @if(request()->query('status')  == 2) selected @endif>Approved</option>
                            <option value="3" @if(request()->query('status')  == 3) selected @endif>Rejected</option>
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
                            <th>Amount</th>
                            <th>Opening Balance</th>
                            <th>Credit Balance</th>
                            <th>Date</th>
                            <th>Remarks</th>
                            <th>Updated By</th>
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
                                    <button value="{{ $value->id }}" type="button" class="btn btn-success btn-sm approveBtn" @if ($value->status == 2 || $value->status != 1)
                                        disabled
                                        @endif
                                        >
                                        <i class="mdi mdi-check menu-icon"></i>
                                    </button>
                                    <button value="{{ $value->id }}" type="button" class="btn btn-danger btn-sm rejectBtn" @if ($value->status == 2 ||
                                        $value->status != 1)
                                        disabled
                                        @endif>
                                        <i class="mdi mdi-trash-can menu-icon"></i>
                                    </button>
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
                            <td>{{ $value->agent->company_name }}
                            </td>
                            <td>{{ $value->agent->code }}
                                @if($value->under_distributor)
                                    <span class="badge badge-pill badge-warning">{{ $value->under_distributor->distributor->code}}</span>
                                @endif
                            </td>
                            <td>{{ $value->amount }}</td>
                            <td>{{$value->current_opening_balance}}</td>
                            <td>{{$value->current_credit_balance}}</td>
                            <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                            <td>{{ $value->remarks }}</td>
                            <td>{{ $value->owner ? $value->owner->first_name : ''}}</td>
                            <td>{{ $value->updated_at }}</td>

                        </tr>

                        @endforeach
                        @else

                        @endif


                    </tbody>
                </table>
                <div>
                    {{ $datas->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal" tabindex="-1" id="rejection-modal" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-body rejection-msg-container">
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
                url: '/accounts/credit-requests/' + id,
                data: {
                    status: 2
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
                type: 'POST',
                url: '/accounts/credit-requests/' + id,
                data: {
                    status: 3
                },
                success: function(resp) {
                    console.log(resp);
                    if (resp.success) {
                        $(".rejection-msg-container").html(resp.message);
                        $("#rejection-modal").modal('show');
                    }
                }
            })
        }

    })

    $(document).on('click','.send-message',function(e) {
        var msg = $("#message").val();
        var credit_request_id = $("#credit_request_id").val();
        $('.send-message').text('processing..');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (msg) {
            $.ajax({
                type: 'POST',
                url: '/accounts/credit-requests-remarks',
                data: {
                    'credit_request_id' : credit_request_id,
                    'message' : msg
                },
                success: function(resp) {
                    console.log(resp);
                    if (resp.success) {
                        $('.send-message').text('SEND MESSAGE');
                        location.reload();
                    }
                }
            })
        }
    });

    $('.datepicker').datepicker({
        autoclose: true
    });
    $('#deposit_from').change(function() {
        let from = $('#deposit_from').val();
        $('#deposit_to').val(from);
    })
    $('.select2').select2({

    });
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
});
</script>
@endsection
