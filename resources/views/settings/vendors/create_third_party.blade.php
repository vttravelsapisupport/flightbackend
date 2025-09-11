@extends('layouts.app')
@section('title','Vendors')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Vendors Details</h4>
            <p class="card-description">
                Third party owners 
            </p>
            <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm mt-4">
                            <tr>
                                <th>Contact Name</th>
                                <td>
                                    <input type="hidden" name="id" value="{{ isset($details) ? $details->id : "" }}">
                                    <input type="text" class="form-control form-control-sm" name="contact_name" value="{{ isset($details) ? $details->name : old('contact_name')  }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="address" value="{{ isset($details) ? $details->address : old('address')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="city" value="{{ isset($details) ? $details->city : old('city')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>
                                    <select name="state" id="state" class="form-control form-control-sm select2" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $key => $value)
                                            <option value="{{ $value }}" @if(isset($details)) @if($value==$details->state_id) selected @endif @endif>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="email" class="form-control form-control-sm" name="email" value="{{ isset($details) ? $details->email : old('email')}}">
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
                                    <input type="text" class="form-control form-control-sm" name="phone" value="{{ isset($details) ? $details->mobile : old('phone')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Current Balance</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="opening_balance" value="{{ isset($details) ? $details->opening_balance : old('opening_balance')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Is Third Party</th>
                                <td>
                                    <input type="checkbox" id="is_third_party" name="is_third_party" value="1" @if(isset($details) && $details->is_third_party) checked @endif>
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
                            @if(isset($details) && $details->additional_phone)
                            @foreach(json_decode($details->additional_phone)    as $key => $val)
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
                            
                            @if(isset($details) && $details->additional_email)
                            @foreach(json_decode($details->additional_email)    as $key => $val)
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
                                    <input type="text" class="form-control form-control-sm" name="whatsapp" value="{{ isset($details) ? $details->whatsapp : old('whatsapp')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>GST No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="gst_no" value="{{ isset($details) ? $details->gst_no : old('gst_no')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>PAN No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="pan_card_no" value="{{ isset($details) ? $details->pan_card_no : old('pan_card_no')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Aadhaar No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="aadhaar_card_no" value="{{ isset($details) ? $details->aadhaar_card_no : old('aadhaar_card_no') }}">
                                </td>
                            </tr>
                           
                            <tr>
                                <th>Referred By</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="referred_by" value="{{ isset($details) ? $details->referred_by : old('referred_by')}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Nearest Airport</th>
                                <td>

                                    <select name="airport" id="airport" class="form-control form-control-sm">
                                        <option value="">Select Nearest Airport</option>
                                        @foreach($airports as $key => $value)
                                            <option value="{{ $value->id }}" @if(isset($details)) @if($value->id == $details->nearest_airport) selected @endif @endif @if (old('airport')) selected @endif>{{ $value->cityName }} - {{ $value->code }} - {{ $value->name }}</option>
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
                                            <option value="{{ $key  }}" @if(isset($details)) @if($key==$details->account_manager_id) selected @endif @endif @if (old('account_manager_id')) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" id="status" class="form-control form-control-sm" required>
                                        <option value="">Select State</option>
                                        <option value="1" @if(isset($details)) @if(1==$details->status) selected @endif @endif>Active</option>
                                        <option value="0" @if(isset($details)) @if(0==$details->status) selected @endif @endif>InActive</option>
                                        <option value="2" @if(isset($details)) @if(2==$details->status) selected @endif @endif>Deactivated</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">Verification</th>
                            </tr>
                           
                            <tr>
                                <th>Pan Card Verification</th>
                                <td>
                                    <input type="checkbox" id="gridCheck1" name="isPANVerified" value="1" @if(isset($details) && $details->isPANVerified) checked @endif>
                                </td>
                            </tr>

                            <tr>
                                <th>GST No Verification</th>
                                <td>
                                    <input  type="checkbox" id="gridCheck1" name="isGSTVerified"  @if(isset($details) && $details->isGSTVerified) checked @endif value="1">
                                </td>
                            </tr>

                            <tr>
                                <th>Aadhaar No Verification</th>
                                <td>  <input  type="checkbox" id="gridCheck1" name="isAadhaarVerified" value="1" @if(isset($details) && $details->isAadhaarVerified) checked @endif></td>
                            </tr>
                            
                            <tr>
                                <th>Email Verification</th>
                                <td><input  type="checkbox" id="gridCheck1" name="isEmailVerified" @if(isset($details) && $details->isEmailVerified) checked @endif value="1">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone Verification</th>
                                <td> <input  type="checkbox" id="gridCheck1" name="isPhoneVerified" @if(isset($details) && $details->isPhoneVerified) checked @endif value="1"></td>
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
