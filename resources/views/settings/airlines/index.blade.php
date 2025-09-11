@extends('layouts.app')
@section('title','Airlines')
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Airlines</h4>
                    <p class="card-description">Airlines in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('airline create')
                    <a href="{{ route('airlines.create') }}" class="btn btn-sm btn-primary">New Airline</a>
                    @endcan
                </div>
            </div>
            <form method="GET" action="{{ route('airlines.index') }}" class="mb-3">
                <div class="form-row">
                    <div class="col">
                        <input type="text" name="code" class="form-control" placeholder="Airline Code" value="{{ request('code') }}">
                    </div>
                    <div class="col">
                        <input type="text" name="name" class="form-control" placeholder="Airline Name" value="{{ request('name') }}">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>isDomestic</th>
                                <th>Infant Charge</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($details as $key => $value)
                            <tr>
                                <td>{{ 1 +$key }}</td>
                                <td>{{ $value->code }}</td>
                                <td>{{ $value->name }}</td>
                                <td>
                                    @if($value->is_domestic == 1)
                                    <div class="badge badge-primary">Domestic</div>
                                    @else
                                    <div class="badge badge-success">International</div>
                                    @endif
                                </td>
                                <td>{{ $value->infant_charge }}</td>
                                <td>
                                    @if($value->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @else
                                    <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('airlines.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                        @can('airline update')
                                        <a href="{{ route('airlines.edit',$value->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-left">
                    {{ $details->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection