@extends('layouts.app')
@section('title','Ticket Service Reports')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" />
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
                        <h4 class="card-title text-uppercase">Ticket Service Reports</h4>
                        <p class="card-description">Ticket Service Reports in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                    </div>
                </div>
                <div class="mb-3">

                </div>

                <form class="forms-sample row" method="GET" action="">
                    <div class="col-md-4">

                        <select name="agent_id" id="agent-select2" class="form-control select2">
                            @if($agent)
                                <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                            <option value="">Select Agent</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="bill_no"
                            placeholder="Enter the Bill No" value="{{ request()->query('bill_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm "
                            name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="from" id="from">
                        <input type="hidden" name="to" id="to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('from') }} - {{ request()->query('to') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="owner_id" id="owner_id" class="form-control select2">
                            <option value="">Select Owner</option>
                            @foreach($owner as $name => $id)
                            <option value="{{ $id}}" @if($id == request()->query('to') ) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mt-1">
                        <select name="third_party_id" id="third_party_id" class="form-control select2">
                            <option value="">Select third Party</option>
                            @foreach($third_party_owner as $name => $id)
                             <option value="{{ $id}}" @if($id == request()->query('third_party_id') ) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mt-1">
                        <select name="limit" id="limit" class="form-control form-control-sm">
                            <option value="100">100</option>
                            <option value="1000">1000</option>
                            <option value="10000">10000</option>
                            <option value="100000">100000</option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-1">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered  table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Agency Name</th>
                                    <th>Agency Code</th>
                                    <th>Bill No</th>
                                    <th>Destination</th>
                                    <th>PNR No.</th>
                                    <th>Vendor</th>
                                    <th>Pax.</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Amounts</th>
                                    <th>Internal Remarks</th>
                                    <th>Agent Remarks</th>
                                    @can('delete-infant-charge')
                                    <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_amount = 0;
                                    $total_pax = 0;
                                @endphp
                                @foreach ($data as $key => $value)
                                    <tr>
                                        <th>{{ 1 + $key }}</th>
                                        <td>{{ $value->company_name }} </td>
                                        <td>{{ $value->code }} </td>
                                        <td>{{ $value->bill_no }}</td>
                                        <td>{{ $value->destination }}</td>
                                        <td>{{ $value->pnr }}</td>

                                        <td
                                        @if($value->is_third_party == 1)
                                        class="bg-warning font-weight-bold"
                                        @endif
                                        @if($value->is_third_party == 2)
                                            class="bg-info font-weight-bold"
                                        @endif
                                        title="
                                    @if($value->is_third_party == 1)Third Party Vendor @endif
                                    @if($value->is_third_party == 2)API Vendors @endif"

                                >{{ $value->owner_name }}</td>
                                        <td>{{ $value->adults + $value->infants }}</td>

                                        <td>{{ $value->additional_service_name }}</td>
                                        <td>{{ $value->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $value->amount }}</td>
                                        @php
                                            $total_amount = $total_amount + $value->amount;
                                        @endphp
                                        <td>{{ $value->internal_remarks }}</td>
                                        <td>{{ $value->external_remarks }}</td>
                                        @can('delete-infant-charge') 
                                        <td>
                                            <button class="btn btn-sm btn-warning btnDelete deleteInfantChargeBtn" value="{{ $value->id }}">Delete</button>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9" class="text-right">Total</th>
                                    <th colspan="1" class="text-right">@money($total_amount)</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div>
                        @if ($data->count() > 0)
                            {{ $data->appends(request()->input())->links() }}
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="alert-container" style="display:none;">
        <div class="alert">
            Selected Items Has Been Deleted
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
    let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-ticket-service-report.csv";
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

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('.select2').select2();
            
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
                $('#dates').val('Date Range')
            @endif

            $('#dates').on('apply.daterangepicker', function(ev, picker) {
                let from = picker.startDate.format('DD-MM-YYYY');
                let to = picker.endDate.format('DD-MM-YYYY');
                // Update hidden fields
                $('#from').val(from);
                $('#to').val(to);
            });

            $("#agent-select2").select2({
                allowClear: false,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                },
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

            $(document).on('click', '.deleteInfantChargeBtn', function(e) {
                e.stopPropagation();

                let resp = confirm("Are you sure you want to delete ?");
                if (!resp) {
                    e.preventDefault();
                } else {
                    let obj = $(this);
                    let id = $(this).val();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/reports/delete-infant-charge',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            'id': id
                        },
                        success: function(success) {
                            if (success) {                            
                                obj.parent().parent().remove();                                
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });        
            
            function msg() {
                var alert = $(".alert-container");
                alert.slideDown();
                window.setTimeout(function() {
                    alert.slideUp();
                }, 2500);
            }

        });
    </script>
@endsection
