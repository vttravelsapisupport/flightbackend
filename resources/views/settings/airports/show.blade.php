@extends('layouts.app')
@section('title','Airports')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Airport Details</h4>
                <p class="card-description">
                    Airport Details of <strong> {{ $details->name }}</strong>
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
                    <label for="name" class="col-sm-3 col-form-label">City Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="city_name" name="city_name" value="{{ $details->cityName }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">City Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="city_code" name="city_code" value="{{ $details->cityCode }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Country Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="country_name" name="country_name" value="{{ $details->countryName }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Country Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="country_code" name="country_code" value="{{ $details->countryCode }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Timezone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="timezone" name="timezone" value="{{ $details->timezone }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Latitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="latitude" name="latitude" value="{{ $details->lat }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Longitude</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="longitude" name="longitude" value="{{ $details->lon }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Number of Airport</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="airport_number" name="airport_number" value="{{ $details->numAirports }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Is City</label>
                    <div class="col-sm-1">
                        <input type="checkbox" @if($details->city) checked @endif class="form-control-plaintext form-control-small" id="is_city" name="is_city" style="width: 17px">
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
