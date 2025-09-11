@extends('layouts.app')
@section('title','Agent Ledger')
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
                    <h4 class="card-title text-uppercase">Agents Ledger</h4>
                    <p class="card-description">
                        Agents Ledger
                    </p>
                </div>

            </div>
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="agent_id" id="agent_id" class="form-control   form-control-sm select2">
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
                        <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control  form-control-sm  datepicker" placeholder="End Date" name="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                    </div>
                </div>

        </div>

        </form>

        <div class="row">
            @if ($data->count() > 0)
            <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Order Type</th>
                            <th>Ref. No</th>
                            <th>Sector</th>
                            <th>PNR</th>
                            <th>No Of Pax</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Desc</th>
                            <th>Medium</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data->count() > 0)
                        @foreach ($data as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger deleteBtn" value="{{ $value->id }}">
                                    Delete
                                </button>
                            </td>
                            <td>{{ $value->created_at->format('d-m-Y') }}</td>
                            <td>
                                @if ($value->type == 1)
                                Temporary Credit
                                @elseif($value->type == 2)
                                Sales
                                @elseif($value->type == 3 )
                                Receipt

                                @elseif($value->type == 4)
                                Refund
                                @elseif($value->type == 5)
                                Temporary Debit

                                @elseif($value->type == 6)
                                Additional Services
                                @endif
                            </td>
                            @if ($value->type == 1)
                            <td class="text-center  font-weight-bold text-success">
                                {{ $value->reference_no }}
                            </td>
                            <td colspan="3" class="text-center  font-weight-bold text-success">
                                Temporary Credit ( {{ $value->amount }} ) </td>
                            @elseif($value->type == 5)
                            <td class="text-center  font-weight-bold text-warning">
                                {{ $value->reference_no }}
                            </td>
                            <td colspan="3" class="text-center  font-weight-bold text-warning">
                                Temporary Debit ( {{ $value->amount }} ) </td>

                            @elseif($value->type == 3)
                            <td class="text-center  font-weight-bold text-warning">
                                RCPT-{{ $value->id }} </td>

                            <td colspan=3> </td>


                            @else

                            <td>
                                @if ($value->ticket_id)
                                {{ $value->ticket->bill_no }}
                                @endif
                            </td>
                            <td>
                                @if ($value->ticket_id)
                                {{ $value->ticket->destination }}
                                @endif
                            </td>
                            <td>
                                @if ($value->ticket_id)
                                {{ $value->ticket->pnr }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($value->ticket_id)
                                {{ $value->ticket->adults }}
                                @endif
                            </td>

                            @endif

                            <td>
                                @if ($value->type == 2)
                                {{ $value->amount }}

                                @elseif($value->type == 6)
                                {{ $value->amount }}
                                @endif
                            </td>
                            <td>

                                @if ($value->type == 3)
                                {{ $value->amount }}
                                @elseif($value->type == 4)
                                {{ $value->amount }}
                                @endif
                            </td>
                            <td>{{ $value->closing_balance }}</td>



                            <td>{{ $value->remarks }}</td>
                            <td>{{ $value->payment_mode }}</td>



                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <th colspan="12" class="text-center">No Result Found</th>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>

                            <th> {{ $datas->appends(request()->except('page'))->links() }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
    $('.select2').select2({

    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.deleteBtn').click(function(e) {
        let id = $(this).val();
        let resp = confirm("Are you sure want delete the Agent Ledger ? ")
        if (resp) {
            $.ajax({
                type: 'POST',
                url: '/ledger-delete/' + id,
                success: function(resp) {
                    if (resp.success) {
                        alert(resp.message);
                        alert("PLEASE RECALCULATE THE BALANCE FROM LEDGER DETAILS");
                        location.reload();
                    }
                }
            })
        }
    });
</script>
@endsection
