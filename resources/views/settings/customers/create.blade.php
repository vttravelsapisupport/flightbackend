@php
use App\Enums\CustomerTypeEnum;
@endphp
@extends('layouts.app')
@section('title','Customers/Distributors')
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
                <h4 class="card-title text-uppercase"> New Customer</h4>
                <p class="card-description">
                    Register a new Customer to the application
                </p>

                <form class="forms-sample row" method="POST" action="{{ route('agents.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label">Customer Type</label>
                            <div class="col-sm-9">
                                <select name="type" id="type" class="form-control">
                                    @foreach(CustomerTypeEnum::cases() as $c)
                                     <option value="{{$c->value}}">{{ $c->getDisplayName()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 col-form-label">Company Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="company_name" placeholder="Enter the Company Name" name="company_name" value="{{ old('company_name') }}" required>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="gst_no" class="col-sm-3 col-form-label">PAN No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="pan_no" placeholder="Enter the PAN No" name="pan_no" value="{{ old('pan_no') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gst_no" class="col-sm-3 col-form-label">Aadhaar No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="aadhaar_no" placeholder="Enter the Aadhaar No" name="aadhaar_no" value="{{ old('aadhaar_no') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gst_no" class="col-sm-3 col-form-label">Gst No</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="gst_no" placeholder="Enter the GST No" name="gst_no" value="{{ old('gst_no') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="airport" class="col-sm-3 col-form-label">Nearest Airport</label>
                            <div class="col-sm-9">
                                <select name="airport" id="airport" class="form-control select2">
                                    <option value="">Select Nearest Airport</option>
                                    @foreach($airports as $key => $value)
                                        <option value="{{ $value->id }}" @if($value->id == old('airport')) selected @endif>{{ $value->cityName }} - {{ $value->code }} - {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="password" placeholder="Enter the Password" name="password" value="{{ old('password') }}" required>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="country" class="col-sm-3 col-form-label">Country</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="country" placeholder="Enter the Country" name="country" readonly value="{{ old('country') ? old('country') : 'India' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="contact_name" class="col-sm-3 col-form-label">Contact Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="contact_name" placeholder="Enter the Contact Name" name="contact_name" value="{{ old('contact_name') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="phone" placeholder="Enter the Phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="whatsapp" class="col-sm-3 col-form-label">Whatsapp</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="whatsapp" placeholder="Enter the Whatsapp" name="whatsapp" value="{{ old('whatsapp') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="city" placeholder="Enter the Email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-3 col-form-label">Address</label>
                            <div class="col-sm-9">
                                <textarea name="address" id="" cols="30" rows="2" class="form-control" placeholder="Enter the Address">{{ old('address') }}</textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-3 col-form-label">Zip Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter the Zip code" maxlength="6" name="zipcode">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="state" class="col-sm-3 col-form-label">State</label>
                            <div class="col-sm-9">
                                <select name="state" id="state" class="form-control select2" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $key => $value)
                                        <option value="{{ $value }}" @if($key== old('state')) selected @endif>{{ $key }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-sm-3 col-form-label">City</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="city" placeholder="Enter the City" name="city" value="{{ old('city') }}" required>
                            </div>
                        </div>
                        

                        {{--<div class="form-group row">--}}
                            {{--<label for="referred_by" class="col-sm-3 col-form-label">Referred By</label>--}}
                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" class="form-control" id="referred_by" placeholder="Enter the Referred By" name="referred_by" value="{{ old('referred_by') }}">--}}
                            {{--</div>--}}
                        {{--</div>--}}


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
            $('.select2').select2({
            });
            $('#phone').change(function(){
                let phone  = $('#phone').val();
                $('#username').val(phone);
            })
        });
    </script>

@endsection
