@extends('layouts.app')
@section('title','PNR History')
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
                    <h4 class="card-title text-uppercase">PNR History</h4>
                    <p class="card-description">Passenger Name Record History in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    <a data-toggle="modal" data-target="#exampleModal-2" class="btn btn-sm btn-info">
                        <i class="mdi mdi-import"></i> Import Excel
                    </a>

                    <button class="btn btn-sm btn-success" id="excelDownload" type="button">
                        <i class="mdi mdi-file-excel"></i>Export CSV
                    </button>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                </div>
                <div id="filterForm" class="col-md-2">
                    <input type="hidden" name="start_date" id="start_date">
                    <input type="hidden" name="end_date" id="end_date">
                    <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                </div>
                <div class="col-md-2">
                    <select name="airline" class="form-control  form-control-sm">
                        <option value="">Airline</option>
                        @foreach($airlines as $airline)
                        <option value="{{$airline->code}}" @if($airline->code == request()->query('airline')  )  selected @endif>{{$airline->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="amount" placeholder="Amount" value="{{ request()->query('amount') }}">
                </div>
                <div class="col-md-2">
                    <select name="result" id="result" class="form-control form-control-sm  select2">
                        <option value="">No. of Result</option>
                        <option value="200" @if ("200" == request()->query('result')) selected @endif>200</option>
                        <option value="300" @if ("300" == request()->query('result')) selected @endif>300</option>
                        <option value="500" @if ("500" == request()->query('result')) selected @endif>500</option>
                        <option value="1000" @if ("1000" == request()->query('result')) selected @endif>1000</option>
                        <option value="5000" @if ("5000" == request()->query('result')) selected @endif>5000</option>
                        <option value="10000" @if ("10000" == request()->query('result')) selected @endif>10000</option>
                        <option value="100000" @if ("100000" == request()->query('result')) selected @endif>100000</option>
                    </select>
                </div>
                <div class="col-md-2">

                    <div class="input-group mb-3">

                        <select name="remarks" id="remarks" class="form-control form-control-sm">
                            <option value="">REMARKS</option>
                            <option value="PURCHASE" @if ("PURCHASE" == request()->query('remarks')) selected @endif>PURCHASE</option>
                            <option value="REPAYMENT" @if ("REPAYMENT" == request()->query('remarks')) selected @endif>REPAYMENT</option>
                            <option value="REFUND" @if ("REFUND" == request()->query('remarks')) selected @endif>REFUND</option>
                            <option value="INFANT" @if ("INFANT" == request()->query('remarks')) selected @endif>INFANT </option>
                            <option value="ADD ON SERVICES" @if ("ADD ON SERVICES" == request()->query('remarks')) selected @endif>ADD ON SERVICES</option>
                            <option value="NO SHOW" @if ("NO SHOW" == request()->query('remarks')) selected @endif>NO SHOW</option>
                            <option value="DECREASE FARE" @if ("DECREASE FARE" == request()->query('remarks')) selected @endif>DECREASE FARE</option>
                            <option value="INCREASE FARE" @if ("INCREASE FARE" == request()->query('remarks')) selected @endif>INCREASE FARE</option>
                            <option value="JOURNAL" @if ("JOURNAL" == request()->query('remarks')) selected @endif>JOURNAL</option>
                            <option value="SPLIT" @if ("SPLIT" == request()->query('remarks')) selected @endif>SPLIT</option>
                            <option value="BLANK" @if ("BLANK" == request()->query('remarks')) selected @endif>BLANK</option>
                        </select>
                        <div class="input-group-prepend">
                            <button class="btn btn-primary btn-sm "> Search</button>
                          </div>
                      </div>
                </div>

            </form>
            <hr>

            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Payment Date</th>
                                <th>PNR</th>
                                <th>Passenger</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Parent PNR</th>
                                <th>Details</th>
                                <th>Airline Code </th>
                                <th>Remarks</th>
                                <th class="action">Edit</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @php
                            $total_debit = 0;
                            $total_credit = 0;
                            @endphp

                            @foreach($datas as $key => $value)
                            @php
                            $pnr = $value->pnr;
                            if($value->parent_pnr) {
                                $pnr = $value->parent_pnr;
                            }
                           // $purchase = $value->getTicketDetails($pnr);
                            $debit = $value->amount < 0 ? $value->amount : 0;
                            $credit = $value->amount > 0 ? $value->amount : 0;
                            $total_debit = $total_debit + $debit;
                            $total_credit = $total_credit + $credit;
                            @endphp
                            <tr @if($value->remarks == 'SPLIT')  style="background-color : #80c4df" @endif>
                                <td>{{ $key + $datas->firstItem() }}</td>

                                <td>{{\Carbon\Carbon::parse($value->payment_date)->format('d-m-Y')}} </td>
                                <td>{{$value->pnr}}</td>
                                <td>{{$value->passenger_name}}</td>
                                <td>{{$value->amount < 0 ? $value->amount : null}}</td>
                                <td>{{$value->amount > 0 ? $value->amount : null}}</td>
                                <td>{{$value->parent_pnr}}</td>
                                <td>
                                    @if($value->quantity)
                                    Qty: {{$value->quantity}} || TD:  {{\Carbon\Carbon::parse($value->travel_date)->format('d-m-Y')}} || SEC : {{$value->destination_name}}
                                    @endif
                                </td>
                                <td>{{$value->airline_code}}</td>
                                <td
                                class="remarks-column"
                                @if($value->remarks == 'PURCHASE')
                                  style="background-color : #0e37a0; color: #fff;"
                                @elseif($value->remarks == 'REPAYMENT')
                                    style="background-color : #c10417; color: #fff;"
                                @elseif($value->remarks == 'INFANT')
                                    style="background-color : #017a00; color: #fff;"
                                @elseif($value->remarks == 'REFUND')
                                    style="background-color : #f24c01; color: #fff;"
                                @elseif($value->remarks == 'ADD ON SERVICES')
                                    style="background-color : #eecf59; color: #000;"
                                @elseif($value->remarks == 'DECREASE FARE')
                                    style="background-color : #9422ac; color: #fff;"
                                @elseif($value->remarks == 'INCREASE FARE')
                                    style="background-color : #5d3211; color: #fff;"
                                @endif
                                >{{$value->remarks}}</td>
                                <td class="action">
                                    <select class="remarks"  data-id="{{$value->id}}">
                                        <option value="">REMARKS</option>
                                        <option value="PURCHASE">PURCHASE</option>
                                        <option value="REPAYMENT">REPAYMENT</option>
                                        <option value="REFUND">REFUND</option>
                                        <option value="INFANT ">INFANT </option>
                                        <option value="ADD ON SERVICES">ADD ON SERVICES</option>
                                        <option value="NO SHOW">NO SHOW</option>
                                        <option value="DECREASE FARE">DECREASE FARE</option>
                                        <option value="INCREASE FARE">INCREASE FARE</option>
                                        <option value="JOURNAL">JOURNAL</option>
                                        <option value="SPLIT">SPLIT</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td>Total</td>
                                <th>{{$total_debit}}</th>
                                <th>{{$total_credit}}</th>
                                <td colspan="5"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $datas->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-2">PNR History Upload </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pnr-history.import') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-body">
                    <p><a href="{{url('/excel/PNR_HISTORY.xlsx')}}">Download Sample</a></p>
                    <select name="airline" class="form-control mb-3">
                        <option value="">Airline</option>
                        @foreach($airlines as $value)
                        <option value="{{$value->code}}" @if(strtolower($value->code) == 'sg') selected @endif>{{$value->name}} </option>
                        @endforeach
                    </select>
                    <input type="file" name="excel" class="form-control-file mb-5">
                    <button type="submit" class="btn btn-success btn-sm">Upload</button>
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
 $(document).ready(function() {
    $('.select2').select2({});
    @if(request()->query('start_date'))
        $('#dates').daterangepicker({
            // startDate: moment(),
            // endDate: moment(),
            showDropdowns: true,
            // maxDate: moment(),
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
            // maxDate: moment(),
            locale: {
                "format": "DD-MM-YYYY",
            }
        });
        $('#dates').val('Payment Date Range')

    @endif

    $('#dates').on('apply.daterangepicker', function(ev, picker) {
        let start_date = picker.startDate.format('DD-MM-YYYY');
        let end_date = picker.endDate.format('DD-MM-YYYY');

        // $(this).val(start_date + ' - ' + end_date);
        // Update hidden fields
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);

        // $('#start_date').trigger('change');
    });

    $('.remarks').change(function(e) {
        let remarks = $(this).val();
        let id = $(this).data('id');
        let currentRow = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/accounts/pnr-history/update',
            data: {
                id: id,
                remarks: remarks
            },
            success: function(resp) {
                currentRow.parent().parent().find('.remarks-column').html(remarks);
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script>
    let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-pnr-histories.csv";
    var showError = true;
    $("#excelDownload").click(function () {
        if ($('#sortable-table-2 tbody').find('tr').length == 0) {
            if (showError) {
                $('#app').prepend(
                    $('<div/>')
                        .attr("role", "alert")
                        .addClass("alert alert-danger")
                        .text("Cannot export an empty data sheet.")
                );
                showError = false;
            }
        } else {
            $("#sortable-table-2").first().table2csv('download',{
                filename: filename,
                excludeColumns:".action",
            });
        }
    });
</script>
@endsection
