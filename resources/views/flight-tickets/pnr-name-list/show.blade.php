@extends('layouts.app')
@section('title','PNR Name List')
@section('contents')
<div class="col-md-12 grid-margin stretch-card" id="app">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 mb-3">
                <div class="btn-group">
                    <button
                        type="button"
                        class="btn btn-info btn-sm"
                        data-toggle="modal"
                        data-target="#exampleModal"
                    >
                        Name List Status
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-toggle="modal"
                        data-target="#IntimationModal"
                    >
                        Intimation
                    </button>
                </div>

                <hr />
                <div class="row">
                    <div class="col-md-6">
                        <form action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        class="form-control form-control-sm form-control form-control-sm-sm"
                                        name="pnr_no"
                                        placeholder="Enter the PNR No"
                                        autocomplete="off"
                                        value="{{ request()->query('pnr_no') }}"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <button
                                        class="btn btn-outline-behance btn-block btn-sm"
                                        name="search"
                                    >
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form
                            method="GET"
                            action="{{ url('flight-tickets/pnr-name-list/'.$data->id.'/email') }}"
                        >
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="inputPassword2" class="sr-only"
                                        >Email</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        value="support@vishaltravels.in"
                                        name="email"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <button
                                        class="btn btn-sm btn-block btn-primary"
                                    >
                                        Send Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-2">
                    <h5 class="text-uppercase">Update Name List</h5>
                    <hr />
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Remarks</th>
                                <th>Name</th>
                                <th>Count of Passenger</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nameliststatus as $key => $val)
                            <tr>
                                <td>&nbsp; {{ 1 + $key }}.</td>
                                <td>
                                    @if( $val->type == 1) Partially send
                                    @elseif($val->type == 2) Fully send
                                    @elseif($val->type == 3) Checked
                                    @elseif($val->type == 4) Partially DB check
                                    @elseif($val->type == 5) Fully DB check
                                    @endif
                                </td>
                                <td>
                                    {{ $val->remarks }}
                                </td>
                                <td>
                                    {{ $val->name }}
                                </td>
                                <td>{{ isset($val->passenger_ids) ? count($val->passenger_ids) : 0; }}</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-12 mb-2">
                    <h4>Flight Details</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="thead-dark">
                                    <th>Sector</th>
                                    <th>Travel Date</th>
                                    <th>DPT</th>
                                    <th>ARV</th>
                                    <th>PNR</th>
                                    <th>No of Pax</th>
                                    <th>Flight No</th>
                                    <th>Vendor Name</th>
                                </tr>
                                <tr>
                                    <th style="font-size: 16px !important">
                                        {{ $data->destination->name }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->travel_date->format('d-m-Y') }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->departure_time }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->arrival_time }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->pnr}}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->quantity }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->flight_no }}
                                    </th>
                                    <th style="font-size: 16px !important">
                                        {{ $data->owner->name }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-md-6">
                            <h4>Passenger Details</h4>
                        </div>
                        <div class="col-md-6 text-right">

                            <button class="btn btn-success" id="excelDownload">
                                <i class="mdi mdi-file-excel"></i>Export Excel
                            </button>

                        </div>
                    </div>
                    @if($data->airline->name == 'SpiceJet')

                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>TYPE</th>
                                <th>Contact Number</th>
                                <th>Date of Birth (DD-MMM-YYYY)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif >
                                <td>{{$key + 1}}
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>

                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 1) ADT
                                    @elseif($val->type == 2) CHD @else INF
                                    @endif
                                </td>
                                   <td>
                                    {{ $val->agentPhone }}
                                </td>
                                <td>{{  ($val->dob) ? $val->dob->format('d-M-Y') : '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @elseif($data->airline->name == 'Akasa Air')

                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>S No</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Pax Type</th>
                                <th>DOB (dd-mmm-yyyy)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif >
                                <td>{{$key + 1}}
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>

                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 1) ADT
                                    @elseif($val->type == 2) CHD @else INF
                                    @endif
                                </td>
                                <td>{{  ($val->dob) ? $val->dob->format('d-M-Y') : '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @elseif($data->airline->name == 3 || $data->airline->id == 2)
                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>TYPE</th>
                                <th>TITLE</th>
                                <th>FIRST NAME</th>
                                <th>LAST NAME</th>
                                <th>DOB</th>
                                <th>GENDER</th>
                                <th>MOBILE NUMBER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif >
                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 1) Adult
                                    @elseif($val->type == 2) child @else Infant
                                    @endif
                                </td>
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>
                                <td>29/10/82</td>
                                <td>
                                    @if(strtolower($val->title) == 'mr' ||
                                    strtolower($val->title) == 'mstr') MALE
                                    @elseif(strtolower($val->title) == 'ms' ||
                                    strtolower($val->title) == 'mrs' ||
                                    strtolower($val->title) == 'miss') FEMALE
                                    @endif
                                </td>
                                <td>{{ $val->agentPhone }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @elseif($data->airline->id == 12)
                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>S No</th>
                                <th>TITLE</th>
                                <th>FIRST NAME</th>
                                <th>LAST NAME</th>
                                <th>PAX TYPE</th>
                                <th>DOB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif >
                                <td>{{$key + 1}}
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>
                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 1) ADT
                                    @elseif($val->type == 2) CHD @else INF
                                    @endif
                                </td>
                                <td>24-May-1990</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @elseif($data->airline->id == 5)
                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>Pax Type</th>
                                <th>Title</th>
                                <th>Gender</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date OF Birth (DD-MMM-YYYY)</th>
                                <th>Contact</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif>
                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 3) Infant
                                    @else Adult @endif
                                </td>
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    @if(strtolower($val->title) == 'mr' ||
                                    strtolower($val->title) == 'mstr') MALE
                                    @elseif( strtolower($val->title) == 'ms' ||
                                    strtolower($val->title) == 'mrs' ||
                                    strtolower($val->title) == 'miss') FEMALE
                                    @endif
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>
                                <td></td>

                                <td>{{ $val->agentPhone }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    @else
                    <table
                        class="table table-sm table-bordered mt-2"
                        id="employeeTable"
                    >
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Agent Name</th>
                                <th>Agent Contact No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psg_details as $key => $val)
                            <tr @if($val->is_refund == 2) class="bg-info" title="Seat Live" @endif>
                                <td>&nbsp; {{ 1 + $key }}.</td>
                                <td @if($val->
                                    type == 3) style="color:red;font-weight:
                                    600" @endif> @if($val->type == 1) Adult
                                    @elseif($val->type == 2) child @else Infant
                                    @endif
                                </td>
                                <td>
                                    {{ $val->title }}
                                </td>
                                <td>
                                    {{ $val->first_name }}
                                </td>
                                <td>
                                    {{ $val->last_name }}
                                </td>
                                <td>{{ $val->agentName }}</td>
                                <td>{{ $val->agentPhone }}</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Name list Status
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form
                    action="{{ route('pnr-name-list.store') }}"
                    method="POST"
                >
                    @csrf
                    <input
                        type="hidden"
                        name="ticket_id"
                        value="{{ $data->id  }}"
                    />

                    <div class="form-group">
                        <select
                            name="type"
                            id=""
                            class="form-control form-control-sm"
                        >
                            <option value="">Select Type</option>
                            <option value="1">Partially Send</option>
                            <option value="2">Fully Send</option>
                            <option value="3">Checked</option>
                            <option value="4">Partially DB Check</option>
                            <option value="5">Fully DB Check</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            name="name"
                            placeholder="Enter the name"
                        />
                    </div>
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            name="remarks"
                            placeholder="Enter the remarks"
                        />
                    </div>
                    <button class="btn btn-block btn-sm btn-success">
                        Update
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div
    class="modal fade"
    id="IntimationModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="IntimationModal"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="IntimationModal1">

                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <namelist-initimation></namelist-initimation>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
 @section('js')

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script type="text/javascript">
    var url = window.location.href;
    if(url.indexOf('?showIntimation') != -1) {
        $('#IntimationModal').modal('show');
    }
    </script>

<script>
    let filename = "{{ $data->pnr}}";
    var showError = true;

    $("#excelDownload").click(function () {
        if ($('#employeeTable tbody').find('tr').length == 0) {
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
              let table = document.getElementById("employeeTable");
              // Convert HTML table to SheetJS workbook
              let wb = XLSX.utils.table_to_book(table);
              // Save as .xlsx file
              XLSX.writeFile(wb, filename + ".xlsx");
        }
    });
</script>

@endsection
