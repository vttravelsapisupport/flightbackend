@extends('layouts.app')
@section('title','Sales Head')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase"> Edit Sales Head</h4>
            <p class="card-description">
               Edit Sales Haed
            </p>

            <form class="forms-sample" method="POST" action="/settings/sales-head/update">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">First Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}">
                        <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="email" name="email" value="{{ $data->email }}" style="pointer-events: none; opacity: 0.7">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="password" name="password" value="{{ $data->password }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone" placeholder="Enter The Phone" name="phone" value="{{ $data->phone }}" style="pointer-events: none; opacity: 0.7;">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Balance</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="balance" placeholder="Enter The Balance" name="balance" value="{{ $data->balance }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" @if($data->status == 1) selected @endif>Active</option>
                            <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
