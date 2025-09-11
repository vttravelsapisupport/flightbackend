@extends('layouts.app')
@section('title','Users')
@section('contents')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Users</h4>
                        <p class="card-description">All Users in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-primary btn-sm"> 
                            <a href="{{route('users.create')}}" style="color:inherit" class="text-decoration-none">
                                New User
                            </a>
                        </button>
                    </div>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Users</a>
                    </li>
                    <li class="nav-item" role="">
                        <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- users tab -->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form class="forms-sample row mb-3" method="GET" action="">
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm " name="name" autocomplete="off" placeholder="Enter the Name" value="{{ request()->query('name') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm " name="phone" autocomplete="off" placeholder="Enter the phone number" value="{{ request()->query('phone') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm " name="email" autocomplete="off" placeholder="Enter the Email" value="{{ request()->query('email') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="role_id" id="role_id" class="form-control form-control-sm" >
                                    <option value="">Select Role</option>
                                    <option value="1" @if ('1' == request()->query('role_id')) selected @endif>ADMINISTRATOR</option>
                                    <option value="2" @if ('2' == request()->query('role_id')) selected @endif>MANAGER</option>
                                    <option value="3" @if ('3' == request()->query('role_id')) selected @endif>STAFF</option>
                                    <option value="4" @if ('4' == request()->query('role_id')) selected @endif>AGENT</option>
                                    <option value="5" @if ('5' == request()->query('role_id')) selected @endif>B2C</option>
                                    <option value="6" @if ('6' == request()->query('role_id')) selected @endif>ACCOUNTS</option>
                                    <option value="7" @if ('7' == request()->query('role_id')) selected @endif>MARKETING</option>
                                    <option value="8" @if ('8' == request()->query('role_id')) selected @endif>DISTRIBUTOR</option>
                                    <option value="9" @if ('9' == request()->query('role_id')) selected @endif>NEW STAFF</option>
                                    <option value="10" @if ('10' == request()->query('role_id')) selected @endif>SUPPLIER</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Created At</th>
                                        <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $key => $value)
                                    <tr>
                                        <td>{{ 1 +$key }}</td>
                                        <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                        <td>{{ $value->phone  }}</td>
                                        <td>{{ $value->email  }}</td>
                                        @php
                                            $role = head($value->getRoleNames()->toArray());
                                        @endphp
                                        <td>{{ ucwords($role)}}</td>

                                        <td>
                                            @if($value->status == 1)
                                                <div class="badge badge-success">Active</div>
                                            @else
                                                <div class="badge badge-danger">Inactive</div>
                                            @endif
                                        </td>

                                        <td > @if($value->last_login) {{ $value->last_login->created_at->format('d-m-Y h:i:s') }} @else  NOT LOGGED IN @endif</td>
                                        <td>@if($value->created_at ){{ $value->created_at->format('d-m-Y h:i:s') }} @else SYSTEM CREATED @endif </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">

                                                <a href="{{ route('users.show',$value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                                
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $details->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection
