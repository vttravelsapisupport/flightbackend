@extends('layouts.app')
@section('title','PNR Name List')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase"> Edit Airline</h4>
                <p class="card-description">
                    Edit the Airline Details of  <strong>{{ $data->name }}</strong>
                </p>
                <form class="forms-sample" method="POST" action="{{ route('airline.update',$data->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Airline name" name="name" value="{{ $data->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea name="description" id="" cols="30" rows="5" class="form-control" placeholder="Enter the Description of the Airline">{{ $data->description }}</textarea>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="domestic" class="col-sm-3 col-form-label">Domestic</label>
                        <div class="col-sm-9">
                            <select name="domestic" id="domestic" class="form-control">
                                <option value="">Select Domestic</option>
                                <option value="1" @if($data->domestic == 1) selected @endif>True</option>
                                <option value="0" @if($data->domestic == 0) selected @endif>False</option>
                            </select>
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
