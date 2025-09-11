@extends('layouts.app')
@section('title','Sales Reports')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">

           @include('partials.sales-report-tab')
            <div class="tab-content" id="myTabContent">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Sales Reports with vendor</h4>
                        <p class="card-description">Sales Reports with vendor in the Appication.</p>
                    </div>

                    <div class="col-md-6 text-right">
                        <div class="mb-3">
                            @if($salesReports)
                            <button class="btn btn-success" id="excelDownload" type="button">
                                <i class="mdi mdi-file-excel"></i>Export Excel
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Agentwise sales report -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="forms-sample row" method="GET" action="{{ url('reports/sales-reports-with-vendor') }}">
                        <div class="col-md-2">
                            <input type="hidden" name="from" id="from">
                            <input type="hidden" name="to" id="to">
                            <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
                        </div>
                       
                        <div class="col-md-2">
                            <button class="btn btn-outline-behance btn-block btn-sm">Search</button>
                        </div>
                    </form>
                    @if($salesReports)
                    <div class="row mt-3">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-bordered  table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Comapny Name</th>
                                        <th>Code</th>
                                        <th>Bill No</th>
                                        <th>Destination Name</th>
                                        <th>PNR No.</th>
                                        <th>Adult</th>
                                        <th>Child</th>
                                        <th>Infant</th>
                                        <th>Adult Charge</th>
                                        <th>Child Charge</th>
                                        <th>Infant Charge</th>
                                        <th>Cost Price</th>
                                        <th>Total Price</th>
                                        <th>Travel Date</th>
                                        <th>Travel Time</th>
                                        <th>Airline </th>
                                        <th>Airline Id</th>
                                        <th>Booking Date</th>
                                        <th>Destination</th>
                                        <th>Vender Name</th>
                                        <th>Pax Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($salesReports as $index => $report)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $report->company_name }}</td>
                                        <td>{{ $report->code }}</td>
                                        <td>{{ $report->bill_no }}</td>
                                        <td>{{ $report->destination_name }}</td>
                                        <td>{{ $report->pnr }}</td>
                                        <td>{{ $report->adults }}</td>
                                        <td>{{ $report->child }}</td>
                                        <td>{{ $report->infants }}</td>
                                        <td>{{ $report->adult_charge }}</td>
                                        <td>{{ $report->child_charge }}</td>
                                        <td>{{ $report->infant_charge }}</td>
                                        <td>{{ $report->cost_price }}</td>
                                        <td>{{ $report->total_price }}</td>
                                        <td>{{ $report->travel_date }}</td>
                                        <td>{{ $report->travel_time }}</td>
                                        <td>{{ $report->airline }}</td>
                                        <td>{{ $report->airline_id }}</td>
                                        <td>{{ $report->BookingDate }}</td>
                                        <td>{{ $report->destination }}</td>
                                        <td>{{ $report->vendor_name }}</td>
                                        <td>{{ $report->pax_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                            <div class="mt-2">

                            </div>
                        </div>
                    </div>
                    @endif
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script>
    let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-sales.csv";
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
            $("#sortable-table-2").first().table2csv({
                filename: filename,


            });
        }
    });
</script>
<script>
    $(document).ready(function() {


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

      $('.select2').select2({});
    });
  </script>

@endsection
