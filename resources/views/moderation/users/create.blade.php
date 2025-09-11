@extends('layouts.app')
@section('title','Users')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase"> New User</h4>
                <p class="card-description">
                    Register a new User to the application
                </p>

                <form class="forms-sample" method="POST" action="{{ route('users.create') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="first_name" class="col-sm-3 col-form-label">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="first_name" placeholder="Enter the First Name" name="first_name" value="{{ old('first_name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="last_name" placeholder="Enter the Last Name" name="last_name" value="{{ old('last_name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="email" placeholder="Enter the Email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="phone" placeholder="Enter the Phone" name="phone" value="{{ old('phone') }}" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="password" placeholder="Enter the password" name="password" value="{{ old('password') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role_id" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                            <select name="role_id" id="role_id" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $key => $value)
                                <option value="{{ $key }}" @if(old('role_id') == $key) selected @endif >{{ ucwords($value) }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
