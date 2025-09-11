@extends('layouts.app')
@section('title','Distributor Ledger')
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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('agent-ledger.index') }}">Agent Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link " href="{{ route('supplier-ledger.index') }}">Supplier Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#home">Distributor Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('supplier-ledger.api') }}">Api Vendor Ledger</a>
                </li>
            </ul>
           <div class="tab-content" id="myTabContent">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Distributor Ledger</h4>
                        <p class="card-description">Distributor’s Ledger in the Appication.</p>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form action="">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select name="distributor_id" id="distributor_id" class="form-control   form-control-sm select2">
                                    @if($agent)
                                        <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                                    @endif
                                    <option value="">Select Distributor</option>
                                    @foreach ($distributors as $key => $val)
                                    <option value="{{ $val->id }}" @if ($val->id == request()->query('distributor_id')) selected @endif>{{ $val->code }}
                                        {{ $val->company_name }} {{ $val->phone }} BL={{ $val->opening_balance }}
                                        CR={{ $val->credit_balance }}

                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" name="start_date" id="start_date">
                                <input type="hidden" name="end_date" id="end_date">
                                <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                            <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order Type</th>
                                        <th>Ref. No</th>
                                        <th>Particular</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        <th>Desc</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $value)
                                    <tr>

                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->created_at->format('d-m-Y h:i:s') }}</td>
                                        <td>

                                            @if ($value->type == 1)
                                            Temp Credit
                                            @elseif($value->type == 2)
                                            Air Ticket
                                            @elseif($value->type == 3 )
                                            Receipt
                                            @elseif($value->type == 4)
                                            Refund
                                            @elseif($value->type == 5)
                                            Temp Debit
                                            @elseif($value->type == 6)
                                            Additional Services
                                            @elseif($value->type == 7)
                                            Distributor Balance
                                            @elseif($value->type == 8)
                                                Distributor Debit
                                            @endif
                                        </td>
                                        @if ($value->type == 1)
                                        <td class="text-center   ">
                                            {{ $value->reference_no }}
                                        </td>
                                        <td  class="text-center  font-weight-bold text-success">
                                            Temp Credit ( {{ $value->amount }} ) </td>
                                        @elseif($value->type == 5)
                                        <td class="text-center   ">
                                            {{ $value->reference_no }}
                                        </td>
                                        <td  class="text-center  font-weight-bold text-warning">
                                            Temp Debit ( {{ $value->amount }} ) </td>
                                        @elseif($value->type == 7)
                                            <td class="text-center   ">{{ $value->reference_no }} </td>
                                            <td  class="text-center  font-weight-bold text-success">
                                                Distributor Balance ( {{ $value->amount }} ) to {{ $value->agent->code }}</td>
                                        @elseif($value->type == 8)
                                            <td class="text-center   ">{{ $value->reference_no }} </td>
                                            <td  class="text-center  font-weight-bold text-danger">
                                                Distributor Debit ( {{ $value->amount }} ) to {{ $value->agent->code }} </td>
                                        @elseif($value->type == 3)
                                        <td class="text-center   ">
                                            RCPT-{{ $value->id }} </td>
                                        <td > </td>
                                        @else

                                        <td class="text-center">
                                            @if ($value->ticket_id)
                                            {{ $value->ticket->adults +  $value->ticket->child + $value->ticket->infants }}
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            @if ($value->type == 2)
                                            {{ $value->amount }}
                                            @elseif($value->type == 6)
                                            {{ $value->amount }}

                                            @elseif($value->type == 7)
                                                {{ $value->amount }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($value->type == 3)
                                            {{ $value->amount }}
                                            @elseif($value->type == 4)
                                            {{ $value->amount }}
                                            @elseif($value->type == 8)
                                                {{ $value->amount }}
                                            @endif

                                        </td>
                                        <td>{{ $value->balance }}
                                        <td>{{ $value->remarks }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <th colspan="12" class="text-center">No Result Found</th>
                                    </tr>
                                    @endif
                                    </td>
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
           </div>
        </div>

       
    </div>
</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    // let start_date =   $('#start_date').val();
    // let end_date   =   $('#end_date').val();
    // if(!start_date && !end_date ){
    //     let today = new Date();
    //     let date1 = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
    //     $('#end_date').val(date1);

    //     let today1       = new Date()
    //     let days         = 86400000
    //     let sevenDaysAgo = new Date(today1 - (30*days))
    //     let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
    //     $('#start_date').val(date2);
    // }

    $('.select2').select2();

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
</script>
@endsection
