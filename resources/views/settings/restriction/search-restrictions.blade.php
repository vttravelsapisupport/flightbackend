@extends('layouts.app')
@section('title','Search Restrictions')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>

    .select2-container .select2-selection--single{
        height:35px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        line-height: 25px !important;
    }
    .amount{
        width:100px;
    }
    .toast.toast-error{
        opacity: 1 !important;
    }

    .toast.toast-success{
        opacity: 1 !important;
    }
</style>

@endsection

@section('contents')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 grid-margin">
                    <h4 class="card-title text-uppercase">Search  Restrictions</h4>
                    <p class="card-description">Search Restrictions in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('airline-sector-restriction create')
                    <a href="/settings/search-restriction" class="btn btn-sm btn-primary mb-1 mr-2">New Search Restrictions</a>
                    @endcan
                </div>
            </div>
            <form class="forms-sample" method="GET" action="">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <select name="type" class="form-control select2">
                            <option value="">Select Type</option>
                            <option value="1" @if (1 == request()->query('type')) selected @endif>Sector</option>
                            <option value="2" @if (2 == request()->query('type')) selected @endif>Airline</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="agent" class="form-control select2">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option
                                    value="{{$agent->id}}"
                                    @if ($agent->id == request()->query('agent')) selected @endif
                                >{{$agent->code}} {{$agent->company_name}} {{$agent->phone}} BL={{$agent->opening_balance}} CR={{$agent->credit_balance}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="flight" class="form-control select2">
                            <option value="">Select Flight</option>
                            @foreach($airlines as $key => $value)
                                <option
                                    value="{{$key}}"
                                    @if ($key == request()->query('flight')) selected @endif
                                >{{$value}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <select name="sector" class="form-control select2">
                            <option value="">Select Sector</option>
                            @foreach($sectors as $key => $value)
                                <option
                                    value="{{$key}}"
                                    @if ($key == request()->query('sector')) selected @endif
                                >{{$value}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <select name="status" class="form-control select2">
                            <option value="">Status</option>
                            <option value="1" @if ('1' == request()->query('status')) selected @endif>Active</option>
                            <option value="0" @if ('0' == request()->query('status')) selected @endif>Inctive</option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-outline-behance btn-block btn-sm mt-1">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Type</th>
                                <th>Flight</th>
                                <th>Sector</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($airlineSectorRestriction as $key => $data)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$data->agent->company_name}}</td>
                                <td>{{$data->type == 1 ? 'Sector' : 'Airline'}}</td>
                                <td>{{isset($data->airline->name) ? $data->airline->name : ''}}</td>
                                <td>{{isset($data->destination->name) ? $data->destination->name : ''}}</td>
                                <td>
                                    @if($data->status == 1)
                                        <div class="badge badge-success">Active</div>
                                    @else
                                        <div class="badge badge-warning">Inactive</div>
                                    @endif
                                </td>
                                <td width="10%">
                                    @can('airline-sector-restriction update')
                                        @if($data->status == 1)
                                            <a href="#" data-id="{{$data->id}}" data-type="deactivate" class="markup-action btn btn-outline-warning btn-sm">Deactivate</a>
                                        @else
                                            <a href="#" data-id="{{$data->id}}" data-type="activate" class="markup-action btn btn-outline-success btn-sm">Activate</a>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function() {

        $('.select2').select2({});

        $(document).on('dblclick', '.amount', function () {
            $(this).removeAttr('readonly');
        });

        $(document).on('focusout', '.amount', function () {
            $(this).attr('readonly', 'readonly');
        });

        $(document).on('change', '#type', function () {
            var agent = $("#agent").val();
            var type = $(this).val();

            $.ajax({
                url: '/settings/get-airline-sector-options',
                type:"POST",
                data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                agent_id: agent,
                type: type
                },
                success:function(response){
                    if(response.success) {
                        $("#airline-sector").html(response.message)
                    }
                },
                error:function (response) {

                }
            });
        });


        $(".markup-action").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var type = $(this).data('type');

            if (!confirm('Are you sure want to '+ type +'?')) {
                return false;
            }

            $.ajax({
                url: '/settings/update-search-restriction-status',
                type:"POST",
                data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    value: type
                },
                success:function(response){
                    if(response.success) {
                        toastr.success('Successfully updated status', { timeOut: 10000 });
                        location.reload();
                    }
                },
                error:function (response) {

                }
            });
        });
    });
</script>

@endsection
