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
                <h4 class="card-title text-uppercase"> Edit Destination</h4>
                <p class="card-description">
                    Edit Destination Details
                </p>

                <form class="forms-sample" method="POST" action="{{ route('destinations.update',$details->id) }}">
                    @csrf 
                    @method('put')
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Destination name" name="name" value="{{ $details->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="code" placeholder="Enter the Code" name="code" value="{{ $details->code }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="airport_id" class="col-sm-3 col-form-label">Origin</label>
                        <div class="col-sm-9">

                            <select name="origin_id" id="origin_id" class="form-control select2">
                                <option value="">Select Origin</option>
                                @foreach($airports as $key => $value)
                                    <option value="{{ $value->id }}" @if($details->origin_id == $value->id) selected @endif>{{ $value->cityName }}  {{ $value->code }} </option>
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
                                    <option value="{{ $value->id }}" @if($details->destination_id == $value->id) selected @endif>{{ $value->cityName }} {{ $value->code }} </option>
                                @endforeach

                            </select>
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

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Is International</label>
                        <div class="col-sm-9">
                            <input type="checkbox" name="is_international" @if($details->is_international) checked @endif/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Revenue Manager</label>
                        <div class="col-sm-9">
                            <select name="manager_id" id="manager_id" class="form-control select2">
                                <option value="">Select Revenue Manager</option>
                                @foreach($managers as $i => $v)
                                <option
                                value="{{$v->id}}"

                                @if($selected_manager)
                                    @if($selected_manager->manager_id === $v->id) selected @endif
                                @endif
                                >{{ $v->first_name }} {{ $v->last_name }} ({{ $v->name }}) {{ $v->email }}  {{ $v->phone }}
                                </option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h4>Name List Managers</h4>
                        <hr>
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Airlines</th>
                                <th>Name List Manager</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($airlines as $k => $a)
                                <tr>
                                    <th>{{ 1+ $k }}</th>
                                    <th>{{ $a->name }}</th>
                                    <th>
                                        <input type="hidden" name="name_list_airline_id[]" value="{{$a->id}}">

                                        <select name="name_list_manager[]" id="name_list_manager" class="form-control select2">
                                            <option value="">Select Name List Manager</option>
                                            @foreach($managers as $i => $v)
                                                <option
                                                    value="{{$v->id}}"
                                                    @if($name_list_managers->count() > 0 )
                                                    @php
                                                    $nlm = $name_list_managers->where('airline_id',$a->id)->first();
                                                    @endphp
                                                        @if($nlm)
                                                            @if($nlm->user_id === $v->id) selected @endif
                                                        @endif
                                                    @endif
                                                >{{ $v->first_name }} {{ $v->last_name }} ({{ $v->name }}) {{ $v->email }}  {{ $v->phone }}
                                                </option>
                                            @endforeach;
                                        </select>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>


                <form class="forms-sample mt-5" method="POST" action="{{ route('destinations.baggage-info', $details->id) }}">
                @csrf
                <div class="form-group row">
                    <h4 class="card-title text-uppercase">Baggage Information</h4>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="status" class="col-sm-4 col-form-label">Airline</label>
                    </div>
                    <div class="col-sm-9">
                        <select name="airline_id" id="airline_id" class="form-control" required>
                            <option value="">Select Airline</option>
                            @foreach($airlines as $airline)
                                <option value="{{$airline->id}}">{{$airline->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="status" class="col-sm-4 col-form-label">Cabin  Baggage</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Domestic</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="cabin_baggage_adult_domestic" value="7kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="cabin_baggage_child_domestic" value="7kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="cabin_baggage_infant_domestic" value="0kg" required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h6>International</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="cabin_baggage_adult_international"  value="7kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="cabin_baggage_child_international"  value="7kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="cabin_baggage_infant_international" value="0kg" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="status" class="col-sm-4 col-form-label">Check-In  Baggage</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Domestic</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="checkin_baggage_adult_domestic"  value="15kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="checkin_baggage_child_domestic"  value="15kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="checkin_baggage_infant_domestic"  value="0kg" required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h6>International</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="checkin_baggage_adult_international"  value="15kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="checkin_baggage_child_international"  value="15kg" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="checkin_baggage_infant_international" value="0kg" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Update</button>
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
