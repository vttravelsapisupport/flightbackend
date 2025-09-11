@extends('layouts.app')
@section('title','Destinations')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase"> New Destination</h4>
                <p class="card-description">
                    Register a new Destination to the application
                </p>

                <form class="forms-sample" method="POST" action="{{ route('destinations.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Destination name" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="code" placeholder="Enter the Code" name="code" value="{{ old('code') }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="airport_id" class="col-sm-3 col-form-label">Origin</label>
                        <div class="col-sm-9">

                            <select name="origin_id" id="origin_id" class="form-control select2">
                                <option value="">Select Origin</option>
                                @foreach($airports as $key => $value)
                                <option value="{{ $value->id }}" @if(old('airport_id') == $value->id) selected @endif>{{ $value->cityName }}  {{ $value->code }} </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="airport_id" class="col-sm-3 col-form-label">Destination</label>
                        <div class="col-sm-9">

                            <select name="destination_id" id="destination_id" class="form-control select2">
                                <option value="">Select Destination</option>
                                @foreach($airports as $key => $value)
                                    <option value="{{ $value->id }}" @if(old('airport_id') == $value->id) selected @endif>{{ $value->cityName }} {{ $value->code }} </option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();




        });
    </script>
@endsection
