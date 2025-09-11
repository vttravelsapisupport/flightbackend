@extends('layouts.app')
@section('title','Users')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Edit User Details</h4>
                <p class="card-description">
                    Edit User Detail of <strong> {{ $details->first_name }} {{ $details->last_name }} </strong>
                </p>

                <form class="forms-sample" method="POST" action="{{ route('users.update', $details->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row">
                        <label for="first_name" class="col-sm-3 col-form-label">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="first_name" placeholder="Enter the First Name" name="first_name" value="{{ $details->first_name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="last_name" placeholder="Enter the Last Name" name="last_name" value="{{ $details->last_name  }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="email" placeholder="Enter the Email" name="email" value="{{ $details->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="phone" placeholder="Enter the Phone" name="phone" value="{{ $details->phone }}" maxlength="10">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role_id" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">

                            <select name="role_id" id="role_id" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $key => $value)
                                    <option value="{{ $key }}" @if(in_array($value,$details->getRoleNames()->toArray()))selected @endif>{{ ucwords($value) }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role_id" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">

                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" @if($details->status == 1) selected @endif>Active</option>
                                <option value="0" @if($details->status == 0) selected @endif>Inactive</option>


                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Update</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
