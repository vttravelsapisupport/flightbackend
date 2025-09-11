@extends('layouts.app')
@section('title','Users')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">User Details</h4>
                        <p class="card-description">
                            User Details of <strong> {{ $details->first_name }} {{ $details->last_name }} </strong>
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('user update')
                            <button type="button" class="btn btn-primary btn-sm"> 
                                <a href="{{ route('users.edit', $details->id) }}" style="color:inherit" class="text-decoration-none">
                                    Edit User
                                </a>
                            </button>
                        @endcan
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#changePasswordModal">
                            Change Password
                        </button>
                    </div>
                </div>

                <div class="row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->first_name }} {{ $details->last_name }}">
                    </div>
                </div>
                <div class="row">
                    <label for="name" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->email }}">
                    </div>
                </div>
                <div class="row">
                    <label for="name" class="col-sm-3 col-form-label">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->phone }}">
                    </div>
                </div>
                <div class="row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        @if($details->status == 1)
                            <div class="badge badge-success">Active</div>
                        @else
                            <div class="badge badge-danger">Inactive</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <label for="role" class="col-sm-3 col-form-label">Role</label>
                    @php
                        $role = head($details->getRoleNames()->toArray());
                    @endphp
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="role" name="role" value="{{ ucwords($role)}}">
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Login Activity</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">User Activity</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <table class="table table-stripped table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>IP</th>
                                <th>User Agent</th>
                                <th>Login Time</th>
                            </tr>

                            </thead>
                            <tbody>
                            @if($user_activity->count() > 0)
                                @foreach($user_activity as $key => $value)
                                    <tr>
                                        <th>{{ 1+ $key }}</th>
                                        <th> {{ $value->ip_address }}</th>
                                        <th> {{ $value->user_agent }}</th>
                                        <th> {{ $value->created_at->format('d-m-Y H:i:s') }}</th>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="4" class="text-center">No Activity</th>
                                </tr>
                            @endif
                            <th></th>
                            </tbody>
                        </table>
                        {{ $user_activity->appends(request()->except('page'))->links() }}
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password - {{ $details->first_name }} {{ $details->last_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.update.password', $details->id) }}" method="POST">
                        @CSRF
                        <input type="hidden" name="id" value="{{ $details->id }}">
                        <div class="form-group">
                            <label for="exampleInputEmail1">New Password</label>
                            <input type="password" class="form-control"  placeholder="Enter New Password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm new Password" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
