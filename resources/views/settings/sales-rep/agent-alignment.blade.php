@extends('layouts.app')
@section('title','Sales Rep')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="card-title text-uppercase">Agent ALignment of {{$salesRep->name}}</h4>
                </div>
            </div>

            <form class="forms-sample row" method="POST" action="/settings/sales-rep/agent-alignment/update">
                @csrf
                <div class="col-md-4">
                    <select name="agency_id" id="agent-select2" class="form-control select2">
                        <option value="">Select Agent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="id" value="{{$salesRep->id}}">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Add</button>
                </div>
            </form>

            <hr>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesRep->agent_alignment as $key => $value)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ ucwords($value->agent->code) }}</td>
                                <td>{{ ucwords($value->agent->company_name) }}</td>
                                <td>{{ $value->agent->email }}</td>
                                <td>{{ ucwords($value->agent->phone)}}</td>
                                <td>{{ $value->agent->opening_balance}}</td>
                                <td>
                                    @if($value->agent->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @else
                                    <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="/settings/sales-rep/agent-alignment/delete/{{$salesRep->id}}/{{$value->id}}" class="btn btn-info btn-xs">Delete</a>
                                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $("#agent-select2").select2({
            allowClear: true,
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
    });
</script>
@endsection
