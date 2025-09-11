@extends('layouts.app')
@section('title','Airlines')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Airline Details</h4>
            <p class="card-description">
                Airline Details of <strong> {{ $details->name }}</strong>
            </p>


            <div class="row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Code</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" id="code" placeholder="Enter the Code" name="code" value="{{ $details->code }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Helpline No</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" id="helpine_no" placeholder="Enter the Helpline No" name="helpine_no" value="{{ $details->helpline_no }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Infant Charge</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control-plaintext" id="infant_charge" placeholder="Enter the Infant Charges" name="infant_charge" value="{{ $details->infant_charge }}">
                </div>
            </div>
            <div class="row">
                <label for="description" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <textarea name="description" id="" cols="30" rows="5" class="form-control-plaintext" placeholder="Enter the Description of the Airline">{{ $details->description }}</textarea>

                </div>
            </div>
            <div class="row">
                <label for="domestic" class="col-sm-3 col-form-label">Domestic</label>
                <div class="col-sm-9">
                    @if($details->is_domestic == 1)
                    <div class="badge badge-primary">Domestic</div>
                    @else
                    <div class="badge badge-success">International</div>
                    @endif
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



        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection