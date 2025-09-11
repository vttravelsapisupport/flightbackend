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
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Search Restrictions</h4><p class="card-description">Create a new Search Restriction in the Appication.</p>
                </div>
                <div class="col-md-6">
                    @can('airline-sector-restriction show')
                    <a href="/settings/search-restrictions" class="btn btn-sm btn-primary mb-4 mr-2 float-right">View Search Restrictions</a>
                    @endcan
                </div>
            </div>
            <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="/settings/search-restriction">
                            @csrf
                            <div class="form-group row">
                                <label for="name"  class="col-sm-3 col-form-label">Agents</label>
                                <div class="col-sm-6">
                                    <select name="agent" id="agent" class="form-control select2">
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                        <option value="{{$agent->id}}">{{$agent->code}} {{$agent->company_name}} {{$agent->phone}} BL={{$agent->opening_balance}} CR={{$agent->credit_balance}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"  class="col-sm-3 col-form-label">Type</label>
                                <div class="col-sm-6">
                                    <select name="type" id="type" class="form-control select2">
                                        <option value="">Select Type</option>
                                        <option value="1">Sector</option>
                                        <option value="2">Airline</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row hidden">
                                <label for="name" class="col-sm-3 col-form-label airline-sector-label">Airline/Sector</label>
                                <div class="col-sm-6">
                                    <select name="airline_sector" class="form-control select2" id="airline-sector">
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                        </form>
                    </div>
                </div>
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

            if(agent != "" && type != "") {
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
            }
        });



        $(document).on('change', '#agent', function () {
            var type = $("#type").val();
            var agent = $(this).val();

            if(agent != "" && type != "") {
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
            }
        });


        $(".markup-action").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var type = $(this).data('type');

            if (!confirm('Are you sure want to '+ type +'?')) {
                return false;
            }

            $.ajax({
                url: '/update-search-restriction-status',
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
