@extends('layouts.app')
@section('title','Airports')
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
                <h4 class="card-title text-uppercase"> Edit Airport</h4>
                <p class="card-description">
                    Edit Airport Details
                </p>

                <form class="forms-sample" method="POST" action="{{ route('airports.edit', $details->id) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Airport name" name="name" value="{{ $details->name }}">
                            <input type="hidden" name="id" value="{{$details->id}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="code" placeholder="Enter the Code" name="code" value="{{ $details->code }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">City Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="city_name" placeholder="Enter the City Name" name="city_name" value="{{ $details->cityName }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">City Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="city_code" placeholder="Enter the City Code" name="city_code" value="{{ $details->cityCode }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Country Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="country_name" placeholder="Enter the Country Name" name="country_name" value="{{ $details->countryName }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Country Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="country_code" placeholder="Enter the Country Code" name="country_code" value="{{ $details->countryCode }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Timezone</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="timezone" placeholder="Enter the Timezone" name="timezone" value="{{ $details->timezone }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="latitude" placeholder="Enter the Latitude" name="latitude" value="{{ $details->lat }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="longitude" placeholder="Enter the Longitude" name="longitude" value="{{ $details->lon }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Number of Airport</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="airport_number" placeholder="Enter Number of Airport" name="airport_number" value="{{ $details->numAirports }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Is City</label>
                        <div class="col-sm-1">
                            <input type="checkbox" @if($details->city) checked @endif class="form-control form-control-small" id="is_city" name="is_city" style="width: 17px">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" @if($details->status == 1) selected @endif>Active</option>
                                <option value="0" @if($details->status == 0) selected @endif>Inactive</option>
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
