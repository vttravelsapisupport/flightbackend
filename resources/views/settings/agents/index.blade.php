@extends('layouts.app')
@section('title','Agents')
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
                    <h4 class="card-title text-uppercase">Agents</h4>
                    <p class="card-description">Agents in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('agent create')
                    <a href="{{ route('agents.create') }}" class="btn btn-sm btn-primary">Register Agent</a>
                    @endcan
                    @can('agents-distributors excel-download')
                        <button class="btn btn-sm btn-success" id="excelDownload">
                            <i class="mdi mdi-file-excel"></i>Excel
                        </button>
                    @endcan
                </div>
            </div>
            <form class="forms-sample row mb-3" method="GET" action="">
                <div class="col-md-4">
                    <select name="agency_id" id="agent-select2" class="form-control select2">
                            @if($agent)
                                <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                            <option value="">Select Agent</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm " name="phone" autocomplete="off" placeholder="Enter the phone number" value="{{ request()->query('phone') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm " name="email" autocomplete="off" placeholder="Enter the Email" value="{{ request()->query('email') }}">
                </div>

                <div class="col-md-2">
                    <select name="airport_id" id="airport_id" class="form-control form-control-sm ">
                        <option value="">Select Airport</option>
                        @foreach($airports as $key => $value)
                        <option value="{{ $value->id }}" @if($value->id == request()->query('airport_id')) selected @endif>{{ $value->cityName }} - {{ $value->code }} - {{ $value->name }}</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="date_from" id="date_from">
                    <input type="hidden" name="date_to" id="date_to">
                    <input type="text" class="form-control form-control-sm" id="dates" placeholder="Booking Date Range" value="{{ request()->query('date_from') }} - {{ request()->query('date_to') }}">
                </div>
                <div class="col-md-2 mt-2">
                    <input type="checkbox" name="exclude_zero" value="1"> Exclude Zero
                </div>
               
                <div class="col-md-2 mt-2">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>

            </form>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="agentTable" class="table table-bordered table-sm ">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Code</th>
                                <th>Agency Name</th>
                                <th>GST No.</th>
                                <th>Balance</th>
                                <th>Credit Balance</th>
                                <th>Credit Shell</th>
                                <th>Credit Limit</th>
                                <th>Contact Person</th>
                                <th>Selling Airport</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Mobile No</th>
                                <th>Email Id</th>
                                <th>Whatsapp No</th>
                                <th>API Enabled</th>
                                <th>Creation date</th>
                                <th>Account Manager</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                            @foreach($data as $key => $value)
                            <tr @if($value->gst_no) class="table-success" @elseif($value->status == 0 || $value->status == 2 || $value->status == 3 || $value->status == 4 ) class="table-danger" @endif>
                                <td>{{ 1 +$key }}</td>

                                <td>{{ $value->code }} </td>
                                <td>{{ $value->company_name }}
                                    @if($value->account_type_id)
                                        @if($value->account_type_id == 4)
                                        <i class="mdi mdi-alpha-d-circle-outline " style="font-size:20px" title=" {{ $value->DistributorName }}"></i>
                                        @elseif($value->account_type_id == 3)
                                            <i class="mdi mdi-alpha-c-circle-outline  menu-icon "  title=" {{ $value->DistributorName }}"></i>
                                        @elseif($value->account_type_id == 2)
                                            <i class="mdi mdi-alpha-b-circle-outline  menu-icon "  title=" {{ $value->DistributorName }}"></i>
                                        @elseif($value->account_type_id == 1)
                                            <i class="mdi mdi-alpha-a-circle-outline  menu-icon " title=" {{ $value->DistributorName }}"></i>
                                        @endif
                                    @endif
                                    @if($value->DistributorName)
                                    <i class="mdi mdi-account-group-outline  menu-icon " style="color: #f3a961;border: 2px solid #f3a961;border-radius: 96px;" title=" {{ $value->DistributorName }}"></i>
                                    @endif </td>
                                <td> {{ $value->gst_no }}</td>
                                <td>{{ $value->opening_balance}}</td>
                                <td>{{ $value->credit_balance}}</td>
                                <td>{{ $value->credit_shell}}</td>
                                <td>{{ $value->credit_limit}}</td>
                                <td>{{ $value->contact_name }}</td>

                                <td>{{ $value->nearest_airport ? $value->airport_city_code : "NA" }} </td>
                                <td>{{ ucwords($value->city) }} </td>
                                <td>{{ $value->state_id ? $value->state_name : "NA"   }} </td>
                                <td>@can('agent show-phone') <span>{{ strtolower($value->phone) }}</span>@else{{ 'xxxxxx'.substr($value->phone,6) }}@endcan </td>
                                <td>@can('agent show-email')<span>{{ strtolower($value->email) }}</span>@else{{ strtolower(Str::limit($value->email,4, 'xxxxxx')) }}@endcan</td>
                                <td>@can('agent show-whatsapp') <span>{{ strtolower($value->whatsapp) }}</span>@else{{ 'xxxxxx'.substr($value->whatsapp,6) }}@endcan  </td>
                                <td>{{ $value->has_api ? "Yes" : "No" }} </td>
                                <td>{{ ucwords($value->created_at->format('d-m-Y h:i:s')) }} </td>
                                <td> @if($value->account_manager_first_name)
                                    {{ $value->account_manager_first_name }} {{ $value->account_manager_last_name }}
                                    @else
                                         NA
                                    @endif
                                </td>
                                <td>@can('agent show-address'){{ $value->address }}@else {{ Str::limit($value->address, $limit = 4, $end = 'xxxxxx') }} @endcan </td>
                                <td>@if($value->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @elseif($value->status == 0)
                                    <div class="badge badge-danger">Inactive</div>
                                    @elseif($value->status == 2)
                                    <div class="badge badge-danger">Dormant</div>
                                    @elseif($value->status == 3)
                                    <div class="badge badge-danger">Duplicate</div>
                                    @elseif($value->status == 4)
                                    <div class="badge badge-danger">B2C</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($value->type == 2)
                                            <a href="{{ route('agents-distributors-alignment.index',['distributor_id' => $value->id]) }}" class="btn btn-outline-secondary btn-sm">Agent Alignment</a>
                                        @endif
                                        @can('agent show')
                                            <a href="{{ route('agents.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                        @endcan
                                        @can('agent update')
                                         <a href="{{ route('agents.edit',$value->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('agent delete')
                                            <form action="{{ route('agents.destroy', $value->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <input class="btn btn-outline-danger btn-sm btnDelete" type="submit" value="Delete" />
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <th colspan="17" class="text-center">No Result Found</th>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $data->appends(request()->except('page'))->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({});

        $('.btnDelete').click((e) => {
            let resp = confirm("Are you sure you want to delete the Agent?");
            if (!resp) {
                e.preventDefault();
            }
        })

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
        @if(request()->query('date_from'))
            $('#dates').daterangepicker({
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            let date_from = '{!! request()->query('date_from') !!}';
                let date_to = '{!! request()->query('date_to') !!}';
                $('#date_from').val(date_from);
                $('#date_to').val(date_to);
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
            let date_from = picker.startDate.format('DD-MM-YYYY');
            let date_to = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#date_from').val(date_from);
            $('#date_to').val(date_to);
        });
    });
    let filename = "agents-distributors-export" + ".csv";
    $("#excelDownload").click(function () {
        if ($('#agentTable tbody').find('tr').length == 0) {
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
            $("#agentTable").first().table2csv({
                filename: filename,
                excludeRows : '.bg-danger'
            });
        }
    });

</script>
@endsection
