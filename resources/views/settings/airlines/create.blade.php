@extends('layouts.app')
@section('title','Airlines')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase"> New Airline</h4>
            <p class="card-description">
                Register a new Airline to the application
            </p>

            <form class="forms-sample" method="POST" action="{{ route('airlines.store') }}">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" placeholder="Enter the Airline name" name="name" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="code" placeholder="Enter the Code" name="code" value="{{ old('code') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Helpline No</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="helpline_no" placeholder="Enter the Helpline No" name="helpline_no" value="{{ old('helpline_no') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Infant Charge</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="infant_charge" placeholder="Enter the Infant Charges" name="infant_charge" value="{{ old('infant_charge') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" id="" cols="30" rows="5" class="form-control" placeholder="Enter the Description of the Airline">{{ old('description') }}</textarea>

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