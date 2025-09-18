@extends('layouts.app')
@section('title','Purchase')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Purchase</h4>
                        <p class="card-description">Purchased Tickets in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-sm btn-success" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <i class="mdi mdi-update"></i> Bulk Update
                        </button>
                        @can('purchase_entry import')
                            <a data-toggle="modal" data-target="#exampleModal-2" class="btn btn-sm btn-info">
                                <i class="mdi mdi-upload"></i> Import Excel
                            </a>
                        @endcan

                        @can('purchase_entry export')
                             <button class="btn btn-sm btn-warning" id="excelPNrHistoryDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export PNRs
                        </button>
                        @endcan
                        @can('purchase_entry export')

                        <button class="btn btn-sm btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                        @endcan
                        @can('purchase_entry create')
                            <a href="{{ route('purchase.create') }}" class="btn btn-sm btn-primary">
                                <i class="mdi mdi-plus "></i> New Ticket Purchase
                            </a>
                        @endcan
                    </div>
                </div>
                <form class="forms-sample row mb-3" method="GET">
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
                        <select name="flight_no" id="flight_no" class="form-control form-control-sm destination select2">
                            <option value="">Select Flight</option>
                            @foreach ($flight_no as $key => $value)
                                <option value="{{ $value->flight_no }}" @if ($value->flight_no == request()->query('flight_no')) selected @endif>
                                    {{ ucwords($value->flight_no) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="travel_date_from" id="travel_date_from">
                        <input type="hidden" name="travel_date_to" id="travel_date_to">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('travel_date_from') }} - {{ request()->query('travel_date_to') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach ($airlines as $key => $value)
                                <option value="{{ $value }}" @if ($value==request()->query('airline')) selected @endif>{{ ucwords($key) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" name="entry_date" id="entry_date" autocomplete="off" placeholder="Enter the Entry Date" value="{{ request()->query('entry_date') }}">
                    </div>
                    <div class="col-md-2 mt-1">
                        <select name="owner_id" id="owner_id" class="form-control form-control-sm  select2">
                            <option value="">Select Owner</option>
                            @foreach ($owners as $id => $name)
                                <option value="{{ $id }}" @if ($id == request()->query('owner_id')) selected @endif>{{ 'SID'.$id .' - '. ucwords($name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mt-1">
                        <select name="supplier_id" id="supplier_id" class="form-control form-control-sm  select2">
                            <option value="">Select Third Party Owner</option>
                            @foreach ($suppliers as $key => $value)
                                <option value="{{ $value->id }}" @if ($value->id == request()->query('supplier_id')) selected @endif>{{ 'SID'.$value->id .' - '. ucwords($value->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mt-1">
                        <select name="result" id="result" class="form-control form-control-sm">
                            <option value="">No. of Result</option>
                            <option value="100" @if ("100" == request()->query('result')) selected @endif>100</option>
                            <option value="300" @if ("300" == request()->query('result')) selected @endif>300</option>
                            <option value="500" @if ("500" == request()->query('result')) selected @endif>500</option>
                            <option value="1000" @if ("1000" == request()->query('result')) selected @endif>1000</option>
                            <option value="5000" @if ("5000" == request()->query('result')) selected @endif>5000</option>
                            <option value="10000" @if ("10000" == request()->query('result')) selected @endif>10000</option>
                            <option value="100000" @if ("100000" == request()->query('result')) selected @endif>100000</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm mt-1"> Search</button>
                    </div>
                </form>
                <!-- <h4 class="card-title text-uppercase" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Bulk Update</h4> -->
                <form action="/flight-tickets/purchase/bulk-update" method="POST" id="formsubmit">
                    @csrf
                    @foreach($data as $key => $val)
                    <input type="hidden" name="id[]" value="{{ $val->id }}">
                    @endforeach
                    <div class="row mb-3 collapse" id="collapseExample">
                        <div class="col-md-2">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm mr-3" name="flight_no" id="flight_no" placeholder="Enter the flight no" value="">
                                <select name="owner_id" id="owner_id_second" class="form-control form-control-sm  select2">
                                    <option value="">Select Owner</option>
                                    @foreach ($owners as $id => $name)
                                        <option value="{{ $id }}" @if ($id == request()->query('owner_id')) selected @endif>{{ 'SID'.$id .' - '. ucwords($name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                    <input type="text" class="form-control form-control-sm mr-3" name="infant_price" id="infant_price" placeholder="infant price" value="">
                                <input type="text" class="form-control form-control-sm mr-3" name="cost_price" id="cost_price" placeholder="cost price" value="">

                                <input type="text" class="form-control form-control-sm" name="base_price" id="base_price" placeholder="base price" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm mr-3" name="departure_time" id="departure_time" placeholder="Departure time" value="">
                                <input type="text" class="form-control form-control-sm mr-3"  name="arrival_time" id="arrival_time" placeholder="Arrival time" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm mr-3" name="checkin_baggage" id="checkin_baggage" placeholder="checkIn(KG)" value="">
                                <input type="text" class="form-control form-control-sm mr-3" name="cabin_baggage" id="cabin_baggage" placeholder="Cabin(KG)" value="">
                                <input type="text" class="form-control form-control-sm mr-3" name="cabin_baggage_count" id="cabin_baggage_count" placeholder="Cabin count" value="">
                                <input type="text" class="form-control form-control-sm" name="checkin_baggage_count" id="checkin_baggage_count" placeholder="CheckIn count" value="">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm mr-3" name="tax" id="tax" placeholder="Enter tax" value="">
                                <select name="flight_status" id="flight_status" class="form-control form-control-sm ml-3">
                                    <option value="">Flight Status</option>
                                    <option value="1">IROP</option>
                                    <option value="2">Cancelled</option>
                                    <option value="3">Ontime</option>
                                </select>
                                <!-- <div class="input-group-prepend"> -->
                                    <button class="btn btn-primary btn-sm ml-3" type="button" id="submitButton">Update</button>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="sortable-table-2">
                        <thead class="thead-dark">
                        <tr>
                            <th class="excludePnrExport">#</th>
                            <th  class="excludePnrExport">Status</th>
                            <th  class="excludePnrExport">Entry Date</th>
                            <th  class="excludePnrExport">Airline</th>
                            <th  class="excludePnrExport">Flight No</th>
                            <th  class="excludePnrExport">Destination</th>
                            <th  class="">PNR No </th>
                            <th  class="">Trip Type </th>
                            <th  class="excludePnrExport">Qty</th>
                            <th  class="excludePnrExport">Cost Price</th>
                            <th  class="excludePnrExport">Sale Price</th>
                            <th  class="excludePnrExport">Infant Price</th>
                            <th  class="excludePnrExport">Travel Date </th>
                            <th  class="excludePnrExport">Arrival Date</th>
                            <th  class="excludePnrExport">DPT</th>
                            <th  class="excludePnrExport">ARV</th>
                            <th  class="excludePnrExport">Vendor</th>
                            <th  class="excludePnrExport">Route</th>
                            <th  class="excludePnrExport">Name List</th>
                            <th width="10%" class="action excludePnrExport">Action</th>
                        </tr>
                        </thead>
                        <tbody class="text-left">

                        @foreach ($data as $key => $value)

                            <tr class="
                             @if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger
                            @elseif($value->namelist_status == 4)
                                table-primary
                            @elseif($value->namelist_status == 5)
                                table-secondary
                            @endif"
                            >
                                <td  class="excludePnrExport">{{ $key + $data->firstItem() }}</td>
                                <td  class="excludePnrExport">

                                    @if($value->FlightStatus)
                                        @if($value->FlightStatus == 1)
                                            <span class="badge badge-warning">IROP</span>
                                        @elseif($value->FlightStatus == 2)
                                            <span class="badge badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-success">On time</span>
                                        @endif
                                    @endif
                                </td>
                                <td  class="excludePnrExport">{{ $value->created_at->format('d-M-Y') }}</td>
                                <td  class="excludePnrExport">{{ ucwords($value->airline_name) }}</td>
                                <td  class="excludePnrExport">{{ ucwords($value->flight_no) }}</td>
                                <td  class="excludePnrExport">{{ ucwords($value->destination_name) }}</td>
                                <td  class="">{{ $value->pnr }} </td>
                                <td  class="">
                                    @if($value->trip_type == 1)
                                    <span class="badge badge-info">One Way</span>
                                    @else
                                    <span class="badge badge-warning">Round Trip</span>
                                    @endif
                                </td>
                                <td  class="excludePnrExport">{{ $value->quantity }}</td>
                                {{--<td  class="excludePnrExport">@money($value->base_price)</td>--}}
                                {{--<td  class="excludePnrExport">@money($value->tax)</td>--}}
                                <td  class="excludePnrExport">@money($value->cost_price)

                                </td>

                                <td  class="excludePnrExport">@money( $value->sell_price)</td>
                                <td  class="excludePnrExport">@money( $value->infant)</td>

                                <td  class="excludePnrExport">{{ $value->travel_date->format('d-M-Y') }}</td>
                                <td  class="excludePnrExport">@if($value->arrival_date) {{ $value->arrival_date->format('d-M-Y') }} @else @endif</td>
                                <td  class="excludePnrExport">{{ $value->departure_time }}</td>

                                <td  class="excludePnrExport">{{ $value->arrival_time }}</td>
                                <td  class="excludePnrExport"
                                    @if($value->owner_type == 2) class="bg-info font-weight-bold" @endif title="@if($value->owner_type == 1)API Vendors  @endif"
                                >{{ ucwords($value->owner_name) }}</td>
                                <td  class="excludePnrExport">{{ $value->flight_route }}</td>
                                <td  class="excludePnrExport">{{ $value->name_list->format('d-M-Y') }}</td>
                                <td class="action excludePnrExport">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('purchase_entry show')
                                                <a href="{{ route('purchase.show', $value->id) }}" class="dropdown-item">View</a>
                                            @endcan
                                            @can('purchase_entry update')
                                                <a href="{{ route('purchase.edit', $value->id) }}" class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('purchase_entry delete')
                                                <form action="{{ route('purchase.destroy',$value->id) }}" method="post">
                                                    <input class="dropdown-item btnDelete" type="submit" value="Delete" @if ($value->sold > 0) disabled @endif />
                                                    <input type="hidden" name="_method" value="delete" />
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                </form>
                                            @endcan
                                            <a class="dropdown-item" href="{{ url('/flight-tickets/purchase/status/'. $value->id) }}">Status</a>
                                        </div>
                                    </div>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="mt-3">
                    @if ($data->count() > 0)
                        {{ $data->appends(request()->except('page'))->links() }}
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-2">Bulk Purchase Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/flight-tickets/purchase/import') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label for="" class="col-form-label col-md-3">Select Source</label>
                                    <div class="col-md-9">
                                        <input type="file" name="excel" class="form-control-file" accept=".xlsx" id="filesize">
                                        <small><a href="{{ asset('/SamplePurchaseTicket.xlsx') }}">
                                                <i class="mdi mdi-download "></i>
                                                Download Excel Format
                                            </a>
                                        </small>
                                        <span id="filesize2"></span>

                                        <br>
                                        <small>
                                            <strong> Note: Delete the first row from Excel format excel sheet </strong>
                                        </small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({});

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            
            @if(request()->query('travel_date_from'))
                $('#dates').daterangepicker({
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                let travel_date_from = '{!! request()->query('travel_date_from') !!}';
                let travel_date_to = '{!! request()->query('travel_date_to') !!}';
                $('#travel_date_from').val(travel_date_from);
                $('#travel_date_to').val(travel_date_to);
                @else
                    $('#dates').daterangepicker({
                    startDate: moment(),
                    endDate: moment(),
                    showDropdowns: true,
                    locale: {
                        "format": "DD-MM-YYYY",
                    }
                });
                $('#dates').val('Travel Date Range')
            @endif

            $('#dates').on('apply.daterangepicker', function(ev, picker) {
                let travel_date_from = picker.startDate.format('DD-MM-YYYY');
                let travel_date_to = picker.endDate.format('DD-MM-YYYY');
                // Update hidden fields
                $('#travel_date_from').val(travel_date_from);
                $('#travel_date_to').val(travel_date_to);
            });


            $('.btnDelete').click((e) => {
                let resp = confirm("Are you sure you want to delete the PurchaseEntry Entry Ticket ?")
                if (!resp) {
                    e.preventDefault();
                }
            })
            $('#submitButton').click((e) => {
                e.preventDefault();
                let promptResp = confirm("Are you sure want to update");
                if (promptResp)
                    $('#formsubmit').submit();
            })
            $('#filesize').on('change', function(evt) {
                let sizeinbytes = this.files[0].size;
                var fSExt = new Array('Bytes', 'KB', 'MB', 'GB');
                let fSize = sizeinbytes; i=0;while(fSize>900){fSize/=1024;i++;}
                $('#filesize2').html(((Math.round(fSize*100)/100)+' '+fSExt[i]))
            })
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
    <script>
        let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-purchase.csv";
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

<script>
        let filename1 =  "pnr-download.csv";
        var showError = true;
        $("#excelPNrHistoryDownload").click(function () {
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
                    filename: filename1,
                    excludeColumns:".excludePnrExport",
                });
            }
        });
    </script>
@endsection
