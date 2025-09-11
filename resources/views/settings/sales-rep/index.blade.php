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
                    <h4 class="card-title text-uppercase">Sales Rep</h4>
                    <p class="card-description">Sales Representatives in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    <a href="/settings/sales-rep/create" class="btn btn-sm btn-primary">New Sales Rep</a>
                </div>
            </div>

            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm " name="name" placeholder="Search By Name"
                    value="{{ request()->query('name')}}">

                </div>
                <div class="col-md-2">
                    <select name="status" id="status" class="form-control form-control-sm airline select2">
                        <option value="">Select Status</option>
                        <option value="1" @if (1== request()->query('status')) selected @endif>Active</option>
                        <option value="2"  @if (0== request()->query('status')) selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>


            </form>
            <hr>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($salesRep as $key => $value)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ ucwords($value->name) }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ ucwords($value->phone)}}</td>
                                <td>{{ $value->balance}}</td>
                                <td>
                                    @if($value->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @else
                                    <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="/settings/sales-rep/edit/{{$value->id}}" class="btn btn-success btn-xs">Edit</a>
                                        <a href="/settings/sales-rep/agent-alignment/{{$value->id}}" class="btn btn-info btn-xs">Agent Alignment</a>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $salesRep->appends(request()->input())->links() }}
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
    });
</script>
@endsection
