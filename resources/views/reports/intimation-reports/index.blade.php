@extends('layouts.app')
@section('title','Intimation Reports')
@section('css')
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css"
    rel="stylesheet"/>
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet"/>
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>

    table {
        width: 150%;
        border-collapse: collapse;
    }
    .tableCustom td, .tableCustom th {
        word-wrap: break-word;
        white-space: pre-wrap;
        max-width: 250px;
        border: 1px solid black !important;
        border-collapse: collapse;
        padding: 5px;
        vertical-align:top
    }
</style>
@endsection @section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Intimation Reports</h4>
                    <p class="card-description">Sales Intimation Reports in the Appication.</p>
                </div>
            </div>

            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <select name="agent_id" id="agent-select2" class="form-control   form-control-sm select2">
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
                        <option value="{{ $key }}" @if ($key==request()->query('airline')) selected @endif>{{ ucwords($value) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" id="status" class="form-control form-control-sm airline">
                        <option value="">Select Status</option>
                        <option value="pending" {{ request()->query('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                        <option value="closed" {{ request()->query('status') == 'closed' ? 'selected' : ''}}>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="from" id="from">
                    <input type="hidden" name="to" id="to">
                    <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
                </div>
              
                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm">
                        Search
                    </button>
                </div>
            </form>

            <div class="row mt-3">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="tableCustom">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Internal Remark</th>
                                <th>Agent Remark</th>
                                <th>Agency Name</th>
                                <th>Bill No</th>
                                <th>Destination</th>
                                <th>PNR No.</th>
                                <th>Pax.</th>
                                <th>Pax Details</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Travel Date</th>
                                <th>Travel Time</th>
                                <th>Arrival Time</th>
                                <th>Airline</th>
                                <th>Booking Date & Time</th>
                                <th>Remarks</th>
                                <th>Subject</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales_ticket_intimation as $key => $val)
                            <tr class=@if($val->status == 0 ) "table-warning" @else "table-success" @endif>
                                <th> {{ 1 + $key }} </th>
                                <td>{{$val->created_at->format('d-m-Y h:i:s') }}</td>
                                <td><div style="display: flex; flex-direction: column;">@if($val->status == 0 )<button class="w-100 p-1 btn btn-sm btn-danger pendingBtn"
                                            type="button" value="{{ $val->sale_ticket_intimation_id }}">Pending</button><button class="w-100 float-left p-1 btn btn-sm btn-primary intimate" value="{{ $val->book_ticket_id }}">Intimate</button>@elseif($val->status == 1)<button class="w-100 p-1 btn btn-sm btn-success "
                                        type="button" value="{{ $val->sale_ticket_intimation_id }}">Closed</button>@endif<button class="w-100 float-left p-1 btn btn-sm btn-info addRemark" value="{{ $val->sale_ticket_intimation_id }}">Remark</button></div></td>

                                <td>@if( $val->InternalRemark){{ $val->InternalRemark }}<br><small>{{ $val->InternalRemarkCreatedBy }}</small><br><small>{{ Carbon\Carbon::parse($val->InternalRemarkCreatedAt)->format('d-m-Y h:i:s')  }}</small>@endif</td>
                                <td>@if( $val->AgentRemark){{ $val->AgentRemark }}<br><small>{{ $val->AgentRemarkCreatedBy }} </small><br><small>{{ Carbon\Carbon::parse($val->AgentRemarkCreatedAt)->format('d-m-Y h:i:s')  }} </small>@endif</td>

                                <td>{{ $val->company_name }} - {{$val->phone }}</td>
                                <td>{{ $val->bill_no }}</td>
                                <td>{{ $val->destination }}</td>
                                <td>{{ $val->pnr }}</td>
                                <td>{{ $val->adults + $val->child }}</td>
                                <td title="{{trim($val->paxDetails)}}"><div style="height: 80px; overflow: hidden;">{{ $val->paxDetails }}</div></td>
                                <td>{{ $val->pax_price }}</td>
                                <td>{{ ($val->adults + $val->child)  * $val->pax_price }}</td>
                                <td style="width: 90px;">{{ Carbon\Carbon::parse($val->travel_date)->format('d-m-Y') }}</td>
                                <td>{{ $val->travel_time }}</td>
                                <td>{{ $val->arrival_time }}</td>
                                <td>{{ $val->airline }}</td>
                                <td>{{ Carbon\Carbon::parse($val->book_ticket_created_at)->format('d-m-Y h:i:s') }}</td>
                                <td title="{{trim($val->remark)}}"><div style="height: 80px; overflow: hidden;">{{ trim($val->remark) }}</div></td>
                                <td title="{{trim($val->subject)}}"><div style="height: 80px; overflow: hidden;">{{ trim($val->subject) }} </div></td>
                                <td title="{{trim($val->content)}}"><div style="height: 80px; overflow: hidden;">{{ trim($val->content) }} </div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $sales_ticket_intimation->appends(request()->input())->links() }}

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Remarks</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/flight-tickets/intimation-remark" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="remark_id" id="remark_id" value="17066">
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

<div class="modal fade show" id="intimationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Intimation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="intimation-form" action="" method="POST">
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
                    <input type="hidden" value="1" name="intimation_list">
                    <button type="submit" class="btn btn-success btn-sm">Send</button>

                </div>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection @section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
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
            // Set the initial value as a placeholder
            $('#dates').val('Select Booking Date Range');
        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let from = picker.startDate.format('DD-MM-YYYY');
            let to = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#from').val(from);
            $('#to').val(to);
        });


        $('.pendingBtn').click(function(){
            let value =  $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/flight-tickets/ajax/intimation-report/status',
                type: 'PUT',
                data:{
                   id:value
                },
                success:function(resp){
                location.reload()
                }
            })
        })

        $(".select2").select2();
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

        $('.addRemark').click(function(e){
            let initimationID = e.target.value;
            console.log(initimationID);
            $('#remark_id').val(initimationID);
            $('#exampleModal').modal('show')
        });

        $(".intimate").click(function(e){
            let book_id = e.target.value;
            var url = "/flight-tickets/sales/initimation/";
            url = url + book_id;
            $('#intimation-form').attr('action', url);
            $('#intimationModal').modal('show')
        })
    });
</script>
@endsection
