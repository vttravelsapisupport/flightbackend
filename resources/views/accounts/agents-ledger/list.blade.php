@extends('layouts.app')
@section('title','Agent Ledger')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
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
            <div class="row">
                @if($datas->count() > 0)
                <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($datas->count() > 0)
                            @foreach($datas as $key => $value)
                            <tr>
                                <td>{{ 1 +$key }}</td>
                                <td>{{ $value->created_at->format('d-m-Y') }}</td>
                                <td>

                                    @if($value->type == 1)
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
                                    @elseif($value->type == 7)
                                    Distributor Balance
                                    @elseif($value->type == 8)
                                    Distributor Debit
                                    @endif
                                </td>
                                <td>{{ $value->amount }}</td>
                                <td>{{ $value->balance }}</td>

                                <td>{{ $value->remarks }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <th colspan="12" class="text-center">No Result Found</th>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
@section('js')

@endsection
