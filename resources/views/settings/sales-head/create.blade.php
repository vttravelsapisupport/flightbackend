@extends('layouts.app')
@section('title','Sales Head')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase"> New Sales Head</h4>
            <p class="card-description">
                Register a new Sales Haed to the application
            </p>

            <form class="forms-sample" method="POST" action="/settings/sales-head/store">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">First Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="first_name" placeholder="Enter The First Name" name="first_name" value="{{ old('first_name') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Last Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="last_name" placeholder="Enter The Last Name" name="last_name" value="{{ old('last_name') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="email" placeholder="Enter The Email" name="email" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="password" placeholder="Enter The Password" name="password" value="{{ old('password') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone" placeholder="Enter The Phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Balance</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="balance" placeholder="Enter The Balance" name="balance" value="{{ old('balance') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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
