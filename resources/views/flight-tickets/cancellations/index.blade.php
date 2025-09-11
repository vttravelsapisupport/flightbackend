@extends('layouts.app')
@section('title','Cancellations')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                    <h4 class="card-title text-uppercase">Cancellations</h4>
                    <p class="card-description">Ticket Cancellation Requests in the Application.</p>
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
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Select Status</option>
                        <option value="1" @if (request()->query('status') == 1) selected @endif>Pending</option>
                        <option value="2" @if (request()->query('status') == 2) selected @endif>Approved</option>
                        <option value="3" @if (request()->query('status') == 3) selected @endif>Rejected</option>
                        <option value="4" @if (request()->query('status') == 4) selected @endif>Seats Live</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>
            </form>
            <div class="btn-group" role="group" aria-label="Basic example">
               <a href="javascript:void(0)" class="btn btn-info btn-sm" id="viewAnchor">View</a>
                @can('refunds update')
                <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="approveAnchor">Approve</a>
                @endcan
                <a href="javascript:void(0)" class="btn btn-success btn-sm" id="liveAnchor">Live Seats</a>
                @can('refunds delete')
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="rejectAnchor">Reject</a>
                @endcan
            </div>
            <div class="btn-group float-right mt-1" role="group" aria-label="Basic example">
                @can('refunds update')
                <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="markApproveAnchor">Mark as Approved</a>
                @endcan
            </div>
            <div class="row mt-3">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered  table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Name</th>
                                <th>Status</th>
                                <th>Sector</th>
                                <th>Airline</th>
                                <th>Travel Date</th>
                                <th>Bill No</th>
                                <th>PNR No</th>
                                <th>Price</th>
                                <th>Vendor</th>
                                <th>Booked On</th>
                                <th>Created On</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_pax_cost = 0;
                                $total_pax = 0;
                            @endphp
                            @foreach ($datas as $key => $value)
                            <tr>
                                <td> <input type="radio" name="checked" value="{{ $value->id }}" @if($value->status == 2 || $value->status == 3)  disabled @endif> </td>
                                <td> {{ $value->company_name }} </td>
                                <td>
                                    @if ($value->status == 1)
                                    <span class="badge badge-info">Pending</span>
                                    @elseif($value->status == 2)
                                    <span class="badge badge-warning">Approved</span>
                                    @elseif($value->status == 3)
                                    <span class="badge badge-danger">Rejected</span>
                                    @else
                                    <span class="badge badge-success">Seat Live</span>
                                    @endif
                                </td>
                                <td> {{ $value->destination }} </td>
                                <td> {{ $value->airline }} </td>
                                <td> {{ date('d-m-Y',strtotime($value->travel_date)) }} </td>
                                <td> {{ $value->bill_no }} </td>
                                <td> {{ $value->pnr }} </td>
                                <td> {{ $value->pax_price }} </td>
                                <td>{{$value->owner_name}}</td>
                                <td>{{ date('d-M-Y h:i:s', strtotime($value->booked_at)) }}</td>
                                <td>{{ date('d-M-Y h:i:s', strtotime($value->created_at)) }}</td>
                                <td> {{ $value->owner ? $value->owner->first_name : '' }} </td>
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

<div class="modal" tabindex="-1" id="pax-modal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Cancellation Request </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pax-details-container">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({

        });
        $('.datepicker').datepicker({
            todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'
        });
        $('#from').change(function() {
            let from = $('#from').val();
            $('#to').val(from);
        });
        $('.btnDelete').click((e) => {
            let resp = confirm("Are you sure you want to delete the Refund Ticket ?")
            if (!resp) {
                e.preventDefault();
            }
        });
        $("input[name='checked']").click(function(e) {
            let id = $.trim(e.target.value);
            $('#viewAnchor').attr('href', '/flight-tickets/refunds/' + id);
            $('#editAnchor').attr('href', '/flight-tickets/refunds/' + id + '/edit');
            $('#deleteForm').attr('action', '/flight-tickets/refunds/' + id);
        });

        $('#rejectAnchor').click(function(e) {
            e.preventDefault();
            let id = $("input[name='checked']:checked").val();

            if(!$("input[name='checked']").is(':checked')) {
                alert('Need to select an option');
                return false;
            }
            let resp = confirm("Are you sure you want Reject the request?");
            $('#rejectAnchor').attr('disabled','disabled');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (resp) {
                $.ajax({
                    type: 'GET',
                    url: '/flight-tickets/cancel-request/' + id,
                    data: {
                        status: 3
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $('#rejectAnchor').removeAttr('disabled');
                            $(".rejection-msg-container").html(resp.message);
                            $("#rejection-modal").modal('show');
                        }else{
                            alert(resp.message);
                        }
                    }
                })
            }
        });


        $('#viewAnchor').click(function(e) {
            e.preventDefault();
            let id = $("input[name='checked']:checked").val();

            if(!$("input[name='checked']").is(':checked')) {
                alert('Need to select an option');
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/flight-tickets/cancel-request-view',
                data: {
                    id: id
                },
                success: function(resp) {
                    if (resp) {
                        $(".pax-details-container").html(resp);
                        $("#pax-modal").modal('show');
                    }
                }
            })

        })

        $("#approveAnchor").click(function(e) {
            e.preventDefault();
            let id = $("input[name='checked']:checked").val();

            if(!$("input[name='checked']").is(':checked')) {
                alert('Need to select an option');
                return false;
            }

            location.replace("/flight-tickets/refund-booking?cancel_request_id="+id);
        });



        $("#liveAnchor").click(function(e) {
            e.preventDefault();
            let id = $("input[name='checked']:checked").val();

            if(!$("input[name='checked']").is(':checked')) {
                alert('Need to select an option');
                return false;
            }

            let resp = confirm("Are you sure want to make the tickets live ?");

            $("#liveAnchor").attr('disabled','disabled');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(resp) {
                $.ajax({
                    type: 'POST',
                    url: '/flight-tickets/refund-booking/live-seats',
                    data: {
                        id: id
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $("#liveAnchor").removeAttr('disabled');
                            $(".rejection-msg-container").html(resp.message);
                            $("#rejection-modal").modal('show');
                        }else{
                            alert(resp.message);
                        }
                    }
                });
            }
        });


        $("#markApproveAnchor").click(function(e) {

            e.preventDefault();

            let id = $("input[name='checked']:checked").val();

            if(!$("input[name='checked']").is(':checked')) {
                alert('Need to select an option');
                return false;
            }

            let resp = confirm("Are you sure want to mark the request as approved?");
            $("#markApproveAnchor").attr('disabled','disabled');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(resp) {
                $.ajax({
                    type: 'POST',
                    url: '/flight-tickets/cancel-request-approved',
                    data: {
                        id: id
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $("#markApproveAnchor").removeAttr('disabled');
                            toastr.success(resp.message, { timeOut: 10000 });
                            window.location.reload();
                        }else{
                            alert(resp.message);
                        }
                    }
                });
            }
        });

        $(document).on('click','.send-message',function(e) {
        var msg = $("#message").val();
        var cancel_request_id = $("#cancel_request_id").val();
        $('.send-message').text('processing..');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (msg) {
            $.ajax({
                type: 'POST',
                url: '/flight-tickets/cancel-request-remarks',
                data: {
                    'cancel_request_id' : cancel_request_id,
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
    });
</script>
@endsection
