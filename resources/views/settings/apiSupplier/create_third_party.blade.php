@extends('layouts.app')
@section('title','API Vendors')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">API Supplier Details</h4>
            <p class="card-description">
                Api owners
            </p>
            <form action="{{ route('api-vendors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm mt-4">
                            <tr>
                                <th>Contact Name</th>
                                <td>
                                    <input type="hidden" name="id" value="{{ isset($data) ? $data->id : "" }}">
                                    <input type="text" class="form-control form-control-sm" name="contact_name" value="{{ isset($data) ? $data->name : old('contact_name')  }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="address" value="{{ isset($data) ? $data->address : old('address')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="city" value="{{ isset($data) ? $data->city : old('city')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>
                                    <select name="state" id="state" class="form-control form-control-sm select2" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $key => $value)
                                            <option value="{{ $value }}" @if(isset($data)) @if($value==$data->state_id) selected @endif @endif>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="email" class="form-control form-control-sm" name="email" value="{{ isset($data) ? $data->email : old('email')}}">
                                </td>
                            </tr>

                            <tr>
                                <th>Password</th>
                                <td>
                                    <input type="password" class="form-control form-control-sm" name="password" value="{{old('password')}}">
                                </td>
                            </tr>

                            <tr>
                                <th>Phone</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="phone" value="{{ isset($data) ? $data->mobile : old('phone')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Current Balance</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="opening_balance" value="{{ isset($data) ? $data->opening_balance : old('opening_balance')}}">
                                </td>
                            </tr>

                            <tr class="table-warning">
                                <th colspan="4" class="text-center text-uppercase">Additonal Contacts</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Whatsapp</th>
                            </tr>
                            @if(isset($data) && $data->additional_phone)
                            @foreach(json_decode($data->additional_phone)    as $key => $val)
                            <tr>

                                <th>{{ 1 + $key}}</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value="{{ $val->name }}"></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value="{{ $val->phone }}"></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value="{{ $val->whatsapp }}"></td>

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <th>1</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value=""></td>

                            </tr>
                            <tr>
                                <th>2</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value=""></td>
                            </tr>
                            <tr>
                                <th>3</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value=""></td>
                            </tr>
                            <tr>
                                <th>4</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value=""></td>
                            </tr>
                            <tr>
                                <th>5</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[name][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[phone][]" value=""></td>
                                <td> <input type="text" class="form-control form-control-sm" name="additional_contact[whatsapp][]" value=""></td>
                            </tr>
                            @endif
                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">Additonal Emails</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Email</th>

                            </tr>

                            @if(isset($data) && $data->additional_email)
                            @foreach(json_decode($data->additional_email)    as $key => $val)
                            <tr>
                                <th>{{ 1 + $key}}</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value="{{$val }}"></td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <th>1</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value=""></td>
                            </tr>
                            <tr>
                                <th>2</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value=""></td>
                            </tr>
                            <tr>
                                <th>3</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value=""></td>
                            </tr>
                            <tr>
                                <th>4</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value=""></td>
                            </tr>
                            <tr>
                                <th>5</th>
                                <td> <input type="text" class="form-control form-control-sm" name="additonal_email[]" value=""></td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-left">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm mt-4">

                            <tr>
                                <th>Whatsapp</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="whatsapp" value="{{ isset($data) ? $data->whatsapp : old('whatsapp')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>GST No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="gst_no" value="{{ isset($data) ? $data->gst_no : old('gst_no')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>PAN No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="pan_card_no" value="{{ isset($data) ? $data->pan_card_no : old('pan_card_no')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Aadhaar No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="aadhaar_card_no" value="{{ isset($data) ? $data->aadhaar_card_no : old('aadhaar_card_no') }}">
                                </td>
                            </tr>

                            <tr>
                                <th>Referred By</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="referred_by" value="{{ isset($data) ? $data->referred_by : old('referred_by')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Nearest Airport</th>
                                <td>

                                    <select name="airport" id="airport" class="form-control form-control-sm">
                                        <option value="">Select Nearest Airport</option>
                                        @foreach($airports as $key => $value)
                                            <option value="{{ $value->id }}" @if(isset($data)) @if($value->id == $data->nearest_airport) selected @endif @endif @if (old('airport')) selected @endif>{{ $value->cityName }} - {{ $value->code }} - {{ $value->name }}</option>
                                        @endforeach
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <th>Account Manager</th>
                                <td>

                                    <select name="account_manager_id" id="account_manager_id" class="form-control form-control-sm">
                                        <option value="">Select Account Manager</option>
                                        @foreach($users as $key => $value)
                                            <option value="{{ $key  }}" @if(isset($data)) @if($key==$data->account_manager_id) selected @endif @endif @if (old('account_manager_id')) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" id="status" class="form-control form-control-sm" required>
                                        <option value="">Select State</option>
                                        <option value="1" @if(isset($data)) @if(1==$data->status) selected @endif @endif>Active</option>
                                        <option value="0" @if(isset($data)) @if(0==$data->status) selected @endif @endif>InActive</option>
                                        <option value="2" @if(isset($data)) @if(2==$data->status) selected @endif @endif>Deactivated</option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">Verification</th>
                            </tr>

                            <tr>
                                <th>Pan Card Verification</th>
                                <td>
                                    <input type="checkbox" id="gridCheck1" name="isPANVerified" value="1" @if(isset($data) && $data->isPANVerified) checked @endif>
                                </td>
                            </tr>

                            <tr>
                                <th>GST No Verification</th>
                                <td>
                                    <input  type="checkbox" id="gridCheck1" name="isGSTVerified"  @if(isset($data) && $data->isGSTVerified) checked @endif value="1">
                                </td>
                            </tr>

                            <tr>
                                <th>Aadhaar No Verification</th>
                                <td>  <input  type="checkbox" id="gridCheck1" name="isAadhaarVerified" value="1" @if(isset($data) && $data->isAadhaarVerified) checked @endif></td>
                            </tr>

                            <tr>
                                <th>Email Verification</th>
                                <td><input  type="checkbox" id="gridCheck1" name="isEmailVerified" @if(isset($data) && $data->isEmailVerified) checked @endif value="1">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone Verification</th>
                                <td> <input  type="checkbox" id="gridCheck1" name="isPhoneVerified" @if(isset($data) && $data->isPhoneVerified) checked @endif value="1"></td>
                            </tr>
                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">Attachments</th>
                            </tr>
                            <tr>
                                <th>
                                    GST
                                </th>
                                <td>
                                    <input type="file" class="form-control" name="gst_file" accept="image/*,.pdf">
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    PAN
                                </th>
                                <td>
                                    <input type="file" class="form-control" name="pan_file" accept="image/*,.pdf">
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    Aadhaar Card
                                </th>
                                <td>
                                    <input type="file" class="form-control" name="aadhaar_card_file" accept="image/*,.pdf">
                                </td>

                            </tr>
                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">API Vendors Data *</th>
                            </tr>
                            <tr>
                                <th>
                                    Owner Balance
                                </th>
                                <td>
                                    <input type="text" class="form-control" name="owner_balance" value="{{ isset($details[0]) ?  $details[0]->owner_balance: ''}}">
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    Markup Amount
                                </th>
                                <td>
                                    <input type="text" class="form-control" name="markup" value="{{ isset($details[0]) ?  $details[0]->markup: ''}}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    User Name
                                </th>
                                <td>
                                    <input type="text" class="form-control" name="user_name" value="{{ isset($details[0]) ?  json_decode($details[0]->credentials)->user_name : ''}}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Password
                                </th>
                                <td>
                                    <input type="text" class="form-control" name="user_password" value="{{ isset($details[0]) ?  json_decode($details[0]->credentials)->password : ''}}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Api Key
                                </th>
                                <td>
                                    <input type="text" class="form-control"  name="api_key" value="{{ isset($details[0]) ?  json_decode($details[0]->credentials)->api_key : ''}}">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>



            </form>




        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
