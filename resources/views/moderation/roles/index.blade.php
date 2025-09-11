@extends('layouts.app')
@section('title','Roles')
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Roles</h4>
                        <p class="card-description">Roles in the application</p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('role create')
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">New Role</a>
                        @endcan
                    </div>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="nav-item" role="">
                        <a class="nav-link active" id="profile-tab1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Roles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- users tab -->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="table-sorter-wrapper col-lg-12 table-responsive">
                            <table id="sortable-table-2" class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th class="sortStyle ascStyle">Name<i class="mdi mdi-chevron-down"></i></th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($details as $key => $value)
                                <tr>
                                    <td>{{ 1+$key }}</td>
                                    <td>{{ ucwords($value->name) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <a href="{{ route('roles.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>

                                            @can('role update')
                                            <a href="{{ route('roles.edit',$value->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                            @endcan
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
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
