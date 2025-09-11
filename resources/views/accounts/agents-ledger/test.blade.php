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


                <div class="row">
                    @if($data->count() > 0)
                    <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                            <thead class="thead-dark">
                            <tr>
                                <th width="10%">#</th>
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
                            @if($data->count() > 0)
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ 1 +$key }}</td>
                                        <td>{{ $value->created_at->format('d-m-Y') }}</td>
                                        <td>
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
                                            @endif
                                        </td>
                                        </td>
                                        @if($value->type == 1)
                                            <td  class="text-center  font-weight-bold text-success"> {{ $value->reference_no }} </td>
                                            <td colspan="3" class="text-center  font-weight-bold text-success"> Temporary Credit </td>
                                        @elseif($value->type == 5)
                                        <td  class="text-center  font-weight-bold text-warning"> {{ $value->reference_no }} </td>
                                            <td colspan="3" class="text-center  font-weight-bold text-warning"> Temporary Debit </td>

                                        @else

                                            <td>
                                                @if($value->ticket_id)
                                                    {{ $value->ticket->bill_no }}
                                                @else($value->type == 3)
                                                {{ $value->reference_no }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->ticket_id)
                                                    {{ $value->ticket->destination }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->ticket_id)
                                                    {{ $value->ticket->pnr }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($value->ticket_id)
                                                    {{ $value->ticket->adults }}
                                                @endif
                                            </td>

                                        @endif

                                        <td>
                                            @if($value->type == 2  )
                                                {{ $value->amount }}
                                            @elseif($value->type == 5)
                                            {{ $value->amount}}
                                            @elseif($value->type == 6)
                                                {{ $value->amount}}
                                            @endif
                                        </td>
                                        <td>

                                            @if($value->type == 3  )
                                                {{ $value->amount}}
                                                @elseif($value->type == 1)
                                                {{ $value->amount }}
                                            @elseif($value->type == 4)
                                            {{$value->amount}}
                                            @endif
                                        </td>
                                        <td>{{ $value->balance }}</td>


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
                                <th>    {{ $data->appends(request()->except('page'))->links() }}</th>
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
    </script>
@endsection
