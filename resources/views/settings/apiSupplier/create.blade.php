@extends('layouts.app')
@section('title','API Vendors')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase"> New Vendor</h4>
                <p class="card-description">
                    Register a new Vendor to the application
                </p>

                <form class="forms-sample" method="POST" action="{{ route('vendors.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Vendor name" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
{{--                    <div class="form-group row">--}}
{{--                        <label for="mobile" class="col-sm-3 col-form-label">Mobile</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" class="form-control" id="mobile" placeholder="Enter the Mobile" name="mobile" value="{{ old('mobile') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="email" class="col-sm-3 col-form-label">Email</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" class="form-control" id="email" placeholder="Enter the Email" name="email" value="{{ old('email') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="city" class="col-sm-3 col-form-label">City</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" class="form-control" id="city" placeholder="Enter the City" name="city" value="{{ old('city') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group row">--}}
{{--                        <label for="password" class="col-sm-3 col-form-label">Password</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" class="form-control" id="password" placeholder="Enter the Password" name="password" value="{{ old('password') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="description" class="col-sm-3 col-form-label">Description</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" class="form-control" id="description" placeholder="Enter the Description" name="description" value="{{ old('description') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group row">--}}
{{--                        <label for="is_third_party" class="col-sm-3 col-form-label">Third Party</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <select name="is_third_party" id="is_third_party" class="form-control">--}}
{{--                                <option value="">Select Third Party</option>--}}
{{--                                <option value="1" @if(old('is_third_party') == 1) selected @endif>Yes</option>--}}
{{--                                <option value="0" @if(old('is_third_party') == 0) selected @endif>No</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
