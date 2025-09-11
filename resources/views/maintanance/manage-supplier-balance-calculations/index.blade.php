@extends('layouts.app')
@section('title','Manage Supplier Balance Calculations')
@section('css')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />

@endsection

@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Manage Supplier Balance Calculations</h4>
                    <p class="card-description">List of balance calculations for selected suppliers.</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <select required name="supplier_ids[]" id="supplier_ids" class="form-control form-control-sm selectpicker" multiple data-live-search="true">
                        @foreach ($data['Suppliers'] as $val)
                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>                
                <div class="col-md-2">
                    <select required name="fys_id" id="fys_id" class="form-control form-control-sm select2">
                        @foreach ($data['FinancialYears'] as $val)
                        <option value="{{ $val->id }}" @if ($val->id == request()->query('fys_id') || $val->isActive == 1) selected @endif>{{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>  
                <!-- <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                </div> -->
                <div class="col-md-2">
                    <input type="text" class="form-control  form-control-sm  datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>
            <hr>
            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>Supplier ID</th>
                                <th>SupplierName</th>                                
                                <th>Supplier Opening Balance</th>
                                <th>Supplier Closing Balance</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @if(!empty($data['BalanceCalculations']))
                            @foreach($data['BalanceCalculations'] as $val)
                            @foreach ($val as $val_1)
                            <tr>
                                <td>{{ $val_1->SupplierID }}</td>
                                <td>{{ $val_1->SupplierName }}</td>
                                <td>{{ $val_1->SupplierOpeningBalance }}</td>
                                <td>{{ $val_1->SupplierClosingBalance }}</td>
                            </tr>
                            @endforeach
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
    </div>
</div>
@endsection

@section('js')
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
    });
</script>
@endsection