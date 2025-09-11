@extends('layouts.app')
@section('title','Destinations')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Destination Details</h4>
                <p class="card-description">
                    Destination Details of <strong> {{ $details->name }}</strong>
                </p>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->code }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="airport_id" class="col-sm-3 col-form-label">Airport</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->destination->name }}">

                    </div>
                </div>
                <div class="form-group row">
                    <label for="airport_id" class="col-sm-3 col-form-label">Airport</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->origin->name }}">

                    </div>
                </div>
                <div class="form-group row">
                    <label for="international" class="col-sm-3 col-form-label">International</label>
                    <div class="col-sm-9">
                        <select name="international" id="international" class="form-control-plaintext">
                            <option value="">Select International</option>
                            <option value="1" @if($details->is_international == 1) selected @endif>True</option>
                            <option value="0" @if($details->is_international == 0) selected @endif>False</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select name="status" id="status" class="form-control-plaintext">
                            <option value="">Select Status</option>
                            <option value="1" @if($details->status == 1) selected @endif>Active</option>
                            <option value="0" @if($details->status == 0) selected @endif>Inactive</option>
                        </select>
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
