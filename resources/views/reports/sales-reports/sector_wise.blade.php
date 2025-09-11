@extends('layouts.app')
@section('title','Sector Wise - Sales Reports')
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
                        <h4 class="card-title text-uppercase">Sales Reports of Sector</h4>
                        <p class="card-description">Sales Reports of Sector in the Application.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                    </div>
                </div>
                <!-- Agentwise sales report -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="forms-sample row" method="GET" action="">
                        <div class="col-md-2">

                            <select name="sector_id" id="sector_id" class="form-control form-control-sm select2">
                                <option value="">Select Destination</option>
                                @foreach($sectors as $k => $v)
                                <option value="{{ $k }}">{{ $v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" name="from" id="from">
                            <input type="hidden" name="to" id="to">
                            <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
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
                            <button class="btn btn-outline-behance btn-block btn-sm">Search</button>
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-bordered  table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th  class="sortStyle"># <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Sector Name <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Adult Count <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Child Count <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Infants Count <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Adult Amount <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Child Amount <i class="mdi mdi-chevron-down"></i></th>
                                        <th  class="sortStyle">Infants Amount <i class="mdi mdi-chevron-down"></i></th>

                                        <th  class="sortStyle"> Total Amount <i class="mdi mdi-chevron-down"></i></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $d)
                                        <tr>
                                            <td>{{ 1 + $k }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->total_adult }} </td>
                                            <td>{{ $d->total_child }} </td>
                                            <td>{{ $d->total_infants }} </td>
                                            <td>{{ $d->total_adults_charge}} </td>
                                            <td>{{ $d->total_child_charge}} </td>
                                            <td>{{ $d->total_infant_charge}} </td>
                                            <td>  {{ $d->total_adults_charge  + $d->total_child_charge +  $d->total_infant_charge }} </td>


                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <div class="mt-2">

                            </div>
                        </div>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
            $('#dates').val('Select Date Range');
        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let from = picker.startDate.format('DD-MM-YYYY');
            let to = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#from').val(from);
            $('#to').val(to);
        });

        $('.select2').select2({});

        $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/flight-tickets/ajax/search/supplier',
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

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script src="{{  asset('assets/js/jq.tablesort.js') }}"></script>
<script>
     if ($('#sortable-table-2').length) {
        $('#sortable-table-2').tablesort();
    }
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
@endsection
