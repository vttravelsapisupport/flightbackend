@extends('layouts.app')
@section('title','Permissions')
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Permissions</h4>
                        <p class="card-description">Permissions in the application</p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('permission create')
                        <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">New Permission</a>
                        @endcan
                    </div>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="nav-item" role="">
                        <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Permissions</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="table-sorter-wrapper col-lg-12 table-responsive">
                                <table id="sortable-table-2" class="table table-striped table-sm">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th >Name</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($details as $key => $value)
                                    <tr>
                                        <td width="5%">{{ 1+ $key }}</td>
                                        <td>{{ $value->name }}</td>

                                        <td width="5%">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('permissions.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                                @can('permission update')
                                                <a href="{{ route('permissions.edit',$value->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                                @endcan
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                {{ $details->links() }}
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
@endsection
