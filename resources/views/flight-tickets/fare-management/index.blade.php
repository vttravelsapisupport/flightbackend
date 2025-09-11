@extends('layouts.app')
@section('title','Fare management')
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
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Fare Management </h4>
                    <p class="card-description">Fare Management in the application</p>
                </div>
                <div class="col-md-6 text-right">

                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
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
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" value="{{ request()->query('pnr_no') }}">
                </div>


                <div class="col-md-2">
                    <select name="owner_id" id="owner_id" class="form-control form-control-sm  select2">
                        <option value="">Select Owner</option>
                        @foreach ($owners as $id => $name)
                            <option value="{{ $id }}" @if ($id == request()->query('owner_id')) selected @endif>{{ 'SID'.$id .' - '.ucwords($name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mt-1">
                    <select name="supplier_id" id="supplier_id" class="form-control form-control-sm  select2">
                        <option value="">Select Third Party Owner</option>
                        @foreach ($suppliers as $key => $value)
                            <option value="{{ $value->id }}" @if ($value->id == request()->query('supplier_id')) selected @endif>{{ 'SID'.$value->id .' - '.ucwords($value->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2  mt-1">
                    <select name="type" id="type"
                            class="form-control form-control-sm destination " style="width:100%">
                        <option value="">Select Type</option>
                        <option value="2" @if (2 == request()->query('type')) selected @endif>Online</option>
                        <option value="1" @if (1== request()->query('type')) selected @endif>Offline</option>

                    </select>
                </div>
                <div class="col-md-2 mt-1">
                    <input type="text" class="form-control  form-control-sm datepicker" name="namelist_date" autocomplete="off" placeholder="Enter the Name list Date" id="namelist_date" value="{{ request()->query('namelist_date') }}">
                </div>
                <div class="col-md-2 mt-1">
                    <select name="namelist_status_id" id="namelist_status_id" class="form-control form-control-sm destination " style="width:100%">
                        <option value="" selected="">Select NameList Status</option>
                        <option value="1" @if (1== request()->query('namelist_status_id')) selected @endif>Partially send</option>
                        <option value="2" @if (2== request()->query('namelist_status_id')) selected @endif>Fully send</option>
                        <option value="3" @if (3== request()->query('namelist_status_id')) selected @endif>Checked</option>
                        <option value="4" @if (4 == request()->query('namelist_status_id')) selected @endif>Pending</option>

                    </select>
                </div>
                <div class="col-md-1">
                    <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="1" name="exclude_zero" @if (request()->query('exclude_zero') == 1) checked @endif>
                            Exclude Zero
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="1" name="show_zero" @if (request()->query('show_zero') == 1) checked @endif>
                            Show Zero
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="1" name="over_booking" @if (request()->query('over_booking') == 1) checked @endif>
                            OB
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>

               
            </form>
            <hr>

            <div class="row">

                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title text-uppercase">Update Fare and Status</h4>
                        </div>
                    </div>
                    <form action="{{ route('fare-management.update', 1) }}" method="POST">
                        @method('put')
                        @csrf
                        @can('fare_management show')
                        <div class="row mb-1">
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm" name="price" id="price" placeholder="Enter the amount" value="{{ old('price') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-control form-control-sm airline" id="type">
                                    <option value="">Select Type</option>
                                    <option value="1" @if (old('type')==1) selected @endif>Fixed Price</option>
                                    <option value="2" @if (old('type')==2) selected @endif>Markup</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control form-control-sm airline" id="status">
                                    <option value="">Select Status</option>
                                    <option value="1" @if (old('status')==1) selected @endif>Offline</option>
                                    <option value="2" @if (old('status')==2) selected @endif>Online</option>


                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary"> Update</button>
                            </div>

                            <div class="col-md-4 text-right">
                                    <a href="{{ url('/flight-tickets/fare-management') .
                                    '?' .
                                    http_build_query([
                                        'airline' => request()->query('airline'),
                                        'flight_no' => request()->query('flight_no'),
                                        'destination_id' => request()->query('destination_id'),
                                        'pnr_no' => request()->query('pnr_no'),
                                        'exclude_zero' => request()->query('exclude_zero'),
                                        'travel_date_from' => Carbon\Carbon::parse(request()->query('travel_date_to'))->subDay()->format('d-m-Y'),
                                        'travel_date_to' => Carbon\Carbon::parse(request()->query('travel_date_to'))->subDay()->format('d-m-Y'),
                                        'previous_day' => true,
                                        'type' => request()->query('type'),
                                        'search' => true,
                                    ]) }}" class="btn btn-sm btn-outline-warning"> <i class="mdi mdi-arrow-left"></i>  Prev
                                                                Day</a> &nbsp;

                                    <a href="{{ url('/flight-tickets/fare-management') .
                                    '?' .
                                    http_build_query([
                                        'airline' => request()->query('airline'),
                                        'flight_no' => request()->query('flight_no'),
                                        'destination_id' => request()->query('destination_id'),
                                        'pnr_no' => request()->query('pnr_no'),
                                        'exclude_zero' => request()->query('exclude_zero'),
                                        'travel_date_from' => Carbon\Carbon::parse(request()->query('travel_date_to'))->addDay()->format('d-m-Y'),
                                        'travel_date_to' => Carbon\Carbon::parse(request()->query('travel_date_to'))->addDay()->format('d-m-Y'),
                                        'next_day' => true,
                                        'search' => true,
                                    ]) }}" class="btn btn-sm btn-outline-warning">Next
                                                                Day <i class="mdi mdi-arrow-right"></i> </a>
                            </div>
                        </div>
                        @endcan

                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Airline</th>
                                    <th>Destination</th>
                                    <th>PNR No</th>
                                    <th>Flight No</th>
                                    <th>Avlb</th>
                                    <th>Block</th>
                                    <th>CP</th>
                                    <th>SP</th>
                                    <th>Type</th>
                                    <th width="7%">New Price</th>
                                    <th>Status</th>
                                    <th>Travel Date</th>
                                    <th>is Refundable </th>
                                    <th>DPT</th>
                                    <th>ARV</th>
                                    <th>Vendor</th>
                                    <th>Route</th>
                                    <th>Name List </th>
                                </tr>
                            </thead>
                            <tbody class="text-left">

                                @foreach ($datas as $key => $value)
                                <tr class=" @if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger
                            @elseif($value->namelist_status == 4)
                                table-primary
                            @elseif($value->namelist_status == 5)
                                table-secondary
                            @endif"
                                >
                                    <td>{{ $key + $datas->firstItem() }} </td>
                                    <td>
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
                                    <td>{{ ucwords($value->airline_name) }}</td>
                                    <td>{{ ucwords($value->destination_name) }}</td>
                                    <td>{{ $value->pnr }}</td>
                                    <td>{{ $value->flight_no }}</td>
                                    <td><label class="@if ($value->available == 0) text-danger @else text-success @endif"><b>{{ $value->available }}</b></label> ({{ $value->quantity }})</td>
                                    <td>{{ $value->blocks }}</td>
                                    <td> {{ $value->cost_price }}</td>
                                    <td> {{ $value->sell_price }}</td>
                                    <td>
                                        @if ($value->isOnline == 2)
                                        <span class="badge badge-success">Online</span>
                                        @elseif($value->isOnline == 1)
                                            <span class="badge badge-info">Offline</span>
                                        @else
                                         <span class="badge badge-danger">Not Set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="hidden" value="{{ $value->sell_price }}" name="sell_price[]" id="sellprice{{ $key }}">
                                        <input type="hidden" value="{{ $value->cost_price }}" name="cost_price[]" id="sellprice{{ $key }}">
                                        <input type="hidden" value="{{ $value->id }}" name="purchase_entry_id[]" id="id{{ $key }}">
                                        <input type="text" class="form-control form-control-sm" name="new_price[]" id="new_price{{ $key }}">
                                    </td>
                                    <td>
                                        <select name="mode[]" id="">
                                            <option value=""> Type</option>
                                            <option value="1"> Offline</option>
                                            <option value="2">Online</option>
                                        </select>
                                    </td>
                                    <td>{{ $value->travel_date->format('d-M-y') }}</td>
                                    <td>

                                            @if ($value->isRefundable == 1)
                                                <button
                                                    class="badge badge-success isRefundableButton"
                                                    type="button"
                                                    value="{{ $value->id }}"
                                                    disabled
                                                >Refundable</button>
                                            @else
                                                <button class="badge badge-grey isNonRefundableButton"
                                                        value="{{ $value->id }}"
                                                         type="button"
                                                        @if($value->airline_id != 1) disabled @endif
                                                >Non Refundable</button>
                                            @endif
                                    </td>
                                    <td>{{ $value->departure_time }}</td>
                                    <td>{{ $value->arrival_time }}</td>
                                    <td @if($value->owner_type == 1) class="bg-warning font-weight-bold" @endif title="@if($value->owner_type == 1)Third Party Vendor  @endif"> {{ ucwords($value->owner_name) }}</td>
                                    <td>{{ $value->flight_route }}</td>
                                    <td>{{ $value->name_list->format('d-m-Y') }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="mt-3">
                    @if ($datas->count() > 0)
                    {{ $datas->appends(request()->input())->links() }}
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
            <form action="{{ url('purchase-entry/import') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label for="" class="col-form-label col-md-3">Select Source</label>
                                <div class="col-md-9">
                                    <input type="file" name="excel" class="form-control-file">
                                    <small><a href="{{ asset('excel/SamplePurchaseTicket.xlsx') }}">
                                            <i class="mdi mdi-download "></i>
                                            Download Excel Format
                                        </a></small>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $('.timepicker').datetimepicker({
            format: 'HH:mm',
        });

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


        $('#bulkUpdate').click(() => {
            bulkUpdate();
        })
        $("#from").change(function() {
		let from = $("#from").val();

		$("#to").val(from);
	});

        function bulkUpdate() {
            let price = $('#price').val();
            let type = $('#type').val();
            let row = $('#sortable-table-2 thead tr').length();
            console.log(row);
            if (price && type) {

            }

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('dblclick', '.isRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 0
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-grey');
                            vm.removeClass('badge-success');
                            vm.addClass('isNonRefundableButton');
                            vm.removeClass('isRefundableButton');
                            vm.html('Non Refundable');
                        }
                    }
                })

            })
            $(document).on('dblclick', '.isNonRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 1
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-success');
                            vm.removeClass('badge-grey');
                            vm.addClass('isRefundableButton');
                            vm.removeClass('isNonRefundableButton');
                            vm.html('Refundable');
                        }
                    }
                })

            })


    });
</script>
@endsection
