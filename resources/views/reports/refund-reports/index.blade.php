@extends('layouts.app')
@section('title','Refund Reports')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Refund Reports</h4>
                    <p class="card-description">Refund Reports in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">

                    <button type="button" id="excelDownload" class="btn btn-sm btn-success"> Export Excel</button>
                </div>

            </div>


            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    {{-- <select name="agent_id" id="agent_id" class="form-control form-control-sm destination">
                            <option value="">Select Agent</option>
                            @foreach ($agents-distributors as $key => $value)
                                <option value="{{ $key }}" @if ($key == request()->query('agent_id')) selected @endif>{{ ucwords($value) }}</option>
                    @endforeach
                    </select> --}}

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
                    <select name="owner_id" id="owner_id" class="form-control   form-control-sm select2">
                        <option value="">Select Supplier</option>
                        @foreach ($owners as $key => $val)
                            <option value="{{ $val->id }}" @if ($val->id == request()->query('owner_id')) selected @endif>{{ $val->code }}
                                {{ $val->name }} {{ $val->mobile }}
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
                        <option value="{{ $value }}" @if ($value==request()->query('airline')) selected @endif>{{ ucwords($value) }}
                        </option>
                        @endforeach
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
                        <select name="limit" id="limit" class="form-control form-control-sm">
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="10000">10000</option>
                            <option value="100000">100000</option>
                        </select>
                    </div>

                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>

            </form>
            <div class="row mt-3">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="export-table" class="table table-bordered  table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Name</th>
                                <th>Supplier</th>
                                <th>Sector</th>
                                <th>Airline</th>
                                <th>Travel Date</th>
                                <th>Bill No</th>
                                <th> PNR No</th>
                                <th>PAX</th>
                                <th>Infant</th>
                                <th>Fare</th>
                                <th>Charge</th>
                                <th>Refund PP</th>
                                <th>Total Refund </th>
                                <th>Infant Refund </th>
                                <th>Refund Date & Time</th>
                                <th>User</th>
                                <th>Remarks</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_pax_cost = 0;
                            $total_pax = 0;
                            @endphp
                            @foreach ($datas as $key => $value)
                            <tr>
                                <td>{{ $key + $datas->firstItem() }}</td>
                                <td>{{ $value->agency_name }}</td>
                                <td>{{ $value->owner_name }}  @if($value->is_third_party == 1) - (Third Party) @elseif($value->is_third_party == 2 )  - (API vendor)@endif</td>
                                <td> {{ $value->destination_name }}</td>
                                <td> {{ $value->airline_name }}</td>
                                <td> {{ Carbon\Carbon::parse($value->travel_date)->format('d-M-Y') }}</td>
                                <td> {{ $value->bill_no }}  </td>
                                <td> {{ $value->pnr }}</td>
                                <td>{{ $value->adult + $value->child }}</td>
                                <td>{{ $value->infant }}</td>
                                <td> {{ $value->pax_price }}  </td>
                                @php
                                $total_pax = $total_pax + $value->pax;
                                $total_pax_cost = $total_pax_cost + $value->pax_cost;
                                @endphp
                                <td>{{ $value->pax_cost }}</td>
                                <td>{{ $value->pax_price - $value->pax_cost }}</td>
                                <td> {{ $value->total_refund }}</td>
                                <td>  {{ $value->infant * $value->infant_charge }} </td>
                                <td>{{ Carbon\Carbon::parse($value->refund_created_at)->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $value->first_name }}</td>
                                <td>{{ $value->remarks }}</td>


                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="text-right">Total</td>
                                <td></td>
                                <td >{{ $total_pax }}</td>
                                <td></td>
                                <td></td>
                                <td >{{ $total_pax_cost }}</td>
                                <td colspan="5"></td>
                            </tr>

                        </tfoot>
                    </table>
                    <div>
                    @if ($datas->count() > 0)
                        {{ $datas->appends(request()->input())->links() }}
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        let filename = "refund-reports.csv";
        $("#excelDownload").click(function () {
            $("#export-table").first().table2csv({
                filename: filename,
            });
        });
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
            $('#dates').val('Booking Date Range')
        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let from = picker.startDate.format('DD-MM-YYYY');
            let to = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#from').val(from);
            $('#to').val(to);
        });
        $('.select2').select2({});

    });
</script>
@endsection
