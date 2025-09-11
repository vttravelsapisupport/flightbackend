@extends('layouts.app')
@section('title','Destinations')
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
                    <h4 class="card-title text-uppercase">Destinations</h4>
                    <p class="card-description">Destinations in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('destination create')
                    <a href="{{ route('destinations.create') }}" class="btn btn-sm btn-primary">New Destination</a>
                    @endcan
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
                        <option value="1" @if (1 == request()->query('status')) selected @endif>Active</option>
                        <option value="2"  @if (2 == request()->query('status')) selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="revenue_manager_id" id="revenue_manager_id" class="form-control form-control-sm  select2">
                        <option value="">Select Revenue Manager</option>
                        @foreach($revenue_managers as $id => $name)
                            <option value="{{$id}}"  @if ($id == request()->query('revenue_manager_id')) selected @endif>{{ $name }}</option>
                        @endforeach
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
                                <th>Code</th>
                                <th>Name</th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Type </th>
                                <th>Revenue Manager</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($details as $key => $value)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ $value->code }}</td>

                                <td>{{ ucwords($value->name) }}</td>
                                <td>{{ ucwords($value->origin->cityName) }} </td>
                                <td>{{ ucwords($value->destination->cityName)}}  </td>
                                <td>
                                    @if($value->is_international == 1)
                                    <div class="badge badge-success">International</div>
                                    @else
                                    <div class="badge badge-danger">Domestic</div>
                                    @endif
                                </td>

                                <td> 
                                     @if($value->manager)
                                         {{ $value->manager->manager->first_name }}
                                         {{ $value->manager->manager->last_name }}
                                     @endif
                                </td>
                                <td>
                                    @if($value->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @else
                                    <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('destinations.show',$value->id) }}" class="btn btn-primary btn-xs">View</a>
                                        @can('destination update')
                                        <a href="{{ route('destinations.edit',$value->id) }}" class="btn btn-success btn-xs">Edit</a>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $details->appends(request()->input())->links() }}
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
