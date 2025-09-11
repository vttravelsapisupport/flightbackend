@extends('layouts.app')
@section('title','Vendors')
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
                        <h4 class="card-title text-uppercase">Vendors</h4>
                        <p class="card-description">Vendors in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('supplier create')
                        <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-primary">New Vendor</a>
                        @endcan
                    </div>
                </div>
                <form class="forms-sample row mb-3" method="GET" action="">
                    <div class="col-md-4">
                        <select name="owner_id" id="owner_id" class="form-control form-control-sm select2">
                            <option value="">Select Vendor</option>
                            @foreach($owners as $key => $val)
                            <option value="{{ $val->id }}" @if($val->id == request()->query('owner_id')) selected @endif>
                               SID-{{ $val->id  }}   {{ $val->name  }} {{ $val->phone }} BL={{ $val->opening_balance }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm " name="mobile" autocomplete="off" placeholder="Enter the phone number" value="{{ request()->query('mobile') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm " name="email" autocomplete="off" placeholder="Enter the Email" value="{{ request()->query('email') }}">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                    </div>
                </form>
                <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="5%" >Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Third Party</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($details as $key => $value)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{'SID-'.$value->id }}</td>
                                <td>{{ ucwords($value->name) }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->mobile }}</td>
                                <td>@money($value->opening_balance)</td>
                                <td>
                                    @if($value->status == 1)
                                        <div class="badge badge-success">Active</div>
                                    @elseif($value->status == 0)
                                        <div class="badge badge-warning">Inactive</div>
                                    @else
                                        <div class="badge badge-danger">Deactivated</div>
                                    @endif
                                </td>
                                <td>{{$value->is_third_party ? 'Yes' : 'No'}}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('vendors.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>

                                        @can('supplier update')
                                        <a href="{{ route('vendors.edit',$value->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $details->appends(request()->except('page'))->links() }}
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

    <script>
        $('.select2').select2({});
    </script>
@endsection
