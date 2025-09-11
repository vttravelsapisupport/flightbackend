@extends('layouts.app')
@section('title','Account Transactions')
@section('css')
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
                <div class="col-md-8">
                    <h4 class="card-title text-uppercase">Re-sync Account Transactions</h4>
                    <p class="card-description">Re-sync Account Transactions for all the agents having transactions on or after the given date (It may take several minutes to complete).</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <select required name="fys_id" id="fys_id" class="form-control form-control-sm select2">
                        @foreach ($data['FinancialYears'] as $val)
                        <option value="{{ $val->id }}" @if ($val->id == request()->query('fys_id') || $val->isActive == 1) selected @endif>{{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm">Sync</button>
                    <a id="btn_update_balance" class="btn btn-primary btn-sm" href="javascript:void(0)">Update Balances</a>
                </div>
            </form>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title text-uppercase">Mismatched Balances</h4>
                    <p>Agents who's running balance is not equal to closing balance</p>
                    <p>Note: Records shown are only for the agents having transactions between selected date and current date</p>
                </div>
            </div>            
            <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Agent ID</th>
                        <th>Agent Code</th>
                        <th>Running Balance</th>
                        <th>Closing Balance</th>
                        <th>Difference</th>
                        <th>Closing Date</th>
                    </tr>
                </thead>
                <tbody class="text-left">
                    @if(!empty($data['BalanceCalculations']))
                    @foreach($data['BalanceCalculations'] as $key=>$val)
                    <tr>
                        <td><input type="checkbox" id="{{ $val->AgentID }}" value="{{ $val->ClosingBalance }}" name="balance[]" class="balance-resync"></td>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $val->AgentID }}</td>
                        <td>{{ $val->AgentCode }}</td>
                        <td>{{ $val->OpeningBalance }}</td>
                        <td>{{ $val->ClosingBalance }}</td>
                        <td>{{ round(($val->OpeningBalance - $val->ClosingBalance), 2) }}</td>
                        <td>{{ $val->EndDate }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td style="text-align: left; padding: 10px 15px 10px;" colspan="31">Nothing to show</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <br/>
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title text-uppercase">Zero Balances</h4>
                    <p>Agents who's closing balance is 0</p>
                    <p>Note: Records shown are only for the agents having transactions between selected date and current date</p>
                </div>
            </div>            
            <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Agent ID</th>
                        <th>Agent Code</th>
                        <th>Running Balance</th>
                        <th>Closing Balance</th>
                        <th>Difference</th>
                        <th>Closing Date</th>
                    </tr>
                </thead>
                <tbody class="text-left">
                    @if(!empty($data['ZeroBalances']))
                    @foreach($data['ZeroBalances'] as $key=>$val)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $val->AgentID }}</td>
                        <td>{{ $val->AgentCode }}</td>
                        <td>{{ $val->OpeningBalance }}</td>
                        <td>{{ $val->ClosingBalance }}</td>
                        <td>{{ round(($val->OpeningBalance - $val->ClosingBalance), 2) }}</td>
                        <td>{{ $val->EndDate }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td style="text-align: left; padding: 10px 15px 10px;" colspan="31">Nothing to show</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        if (!start_date && !end_date) {
            let today = new Date();
            let date1 = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
            $('#end_date').val(date1);

            let today1 = new Date()
            let days = 86400000
            let sevenDaysAgo = new Date(today1 - (30 * days))
            let date2 = sevenDaysAgo.getFullYear() + '-' + (sevenDaysAgo.getMonth() + 1) + '-' + sevenDaysAgo.getDate();
            $('#start_date').val(date2);
        }

        $(".datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#start_date').change(function() {
            let start_date = $('#start_date').val();
            let start_date_year = start_date.split('-')[0];
            let start_date_month = start_date.split('-')[1];
            let start_date_day = start_date.split('-')[2];

            let endDate = $('#end_date');
            endDate.datepicker('destroy');
            endDate.datepicker({
                format: 'yyyy-mm-dd',
                startDate: new Date(start_date_year + '-' + start_date_month + '-' + start_date_day)
            });
            endDate.val(start_date);
            //endDate.attr("required", "true");
        })

        $(document).on('click', '#btn_update_balance', function() {
            var selectedIds = [];
            
            $('.balance-resync:checked').each(function() {
                var agentId = $(this).attr('id');
                var balance = $(this).attr('value');
                
                selectedIds.push({
                    'agent_id': agentId,
                    'balance': balance
                });
            });
            
            console.log(selectedIds);
        });


    });
</script>
@endsection
