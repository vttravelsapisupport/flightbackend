@extends('layouts.app')
@section('title','Online Transactions')
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
                        <h4 class="card-title text-uppercase">Online Transactions</h4>
                        <p class="card-description">Online Transactions in the Appication.</p>
                    </div>
                </div>
                <form action="">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select name="agent_id" id="agent-select2" class="form-control select2">
                                <option value="">Select Agent</option>
                                @foreach ($agents as $key => $val)
                                    <option value="{{ $val->id }}" @if ($val->id == request()->query('agent_id')) selected @endif>{{ $val->code }}
                                        {{ $val->company_name }} {{ $val->phone }} BL={{ $val->opening_balance }}
                                        CR={{ $val->credit_balance }}</option>
                                @endforeach
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
                                    <th>Agent name </th>
                                    <th>Agent Code</th>
                                    <th>Transaction Date and Time</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Surcharge</th>
                                    <th>Total Amount</th>


                                    <th>Card Number</th>




                                    <th>Bank TXN</th>
                                    <th>MMP TXN ID</th>
                                    <th>Bank Name</th>



                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $val)
                                        <tr>
                                            <td>{{ 1 + $key }}</td>
                                            <td><a href="{{ route('agents.show', $val->agent->id) }}">{{ $val->agent->company_name }}
                                                </a></td>
                                            <td>{{ $val->agent->code }}</td>
                                            <td>{{ $val->created_at->format('d-m-y h:i:s') }} </td>
                                            <td>{{ $val->transaction_id }}</td>
                                            <td>
                                                @if ($val->status == 0)
                                                    <span class="badge badge-danger">Failed</span>
                                                @elseif($val->status == 1)
                                                    <span class="badge badge-success">Success</span>
                                                @else
                                                    <span class="badge badge-primary">{{ $val->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $val->amt }}</td>
                                            <td>{{ $val->surcharge }}</td>
                                            <td>{{ $val->total_amount }}</td>

                                            <td>{{ $val->card_number }}</td>



                                            <td>{{ $val->bank_txn }}</td>
                                            <td>{{ $val->mmp_txn }} </td>
                                            <td>{{ $val->bank_name }} </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="12" class="text-center">No Result Found</th>
                                    </tr>
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
    </div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    var today = new Date();
    var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
    $('#end_date').val(date);

    var today = new Date()
    var days = 86400000 //number of milliseconds in a day
    var sevenDaysAgo = new Date(today - (7*days))

    var date= sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();

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
        $('#dates').val('Date Range')

    @endif

    $('#dates').on('apply.daterangepicker', function(ev, picker) {
        let start_date = picker.startDate.format('DD-MM-YYYY');
        let end_date = picker.endDate.format('DD-MM-YYYY');
        // Update hidden fields
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
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
</script>
@endsection
