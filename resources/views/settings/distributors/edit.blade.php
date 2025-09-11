@extends('layouts.app')
@section('title','Agents/Distributors')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Agent / Distributor Details</h4>
            <p class="card-description">
                Agent / Distributor Details of <strong> {{ $data->company_name }}</strong>
            </p>
            <form action="{{ route('agents-distributors.update',$data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm mt-4">
                            <tr>
                                <th>Type</th>
                                <td>
                                    <select name="type" id="" class="form-control form-control-sm" disabled>
                                        <option value="1" @if($data->type == 1) selected @endif >Agent</option>
                                        <option value="2" @if($data->type == 2) selected @endif >Distributor</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Company Name</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="company_name" value="{{ $data->company_name }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Contact Name</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="contact_name" value="{{ $data->contact_name }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="address" value="{{ $data->address }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Zip Code</th>
                                <td>
                                    <input type="text" class="form-control" placeholder="zip code" maxlength="6"  name="zipcode" value="{{ $data->zipcode }}">
                                </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="city" value="{{ $data->city }}">
                                </td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>

                                    <select name="state" id="state" class="form-control form-control-sm select2" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $key => $value)
                                            <option value="{{ $value }}" @if($value==$data->state_id) selected @endif>{{ $key }}</option>
                                        @endforeach

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="email" value="{{ $data->email }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="phone" value="{{ $data->phone }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Current Balance</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="opening_balance" value="{{ $data->opening_balance }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Credit Balance</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="credit_balance" value="{{ $data->credit_balance }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Credit Limit</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="credit_limit" value="{{ $data->credit_limit }}">
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
                            @if($data->additional_phone)
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

                            @if($data->additional_email)
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
                                    <button class="btn btn-primary btn-sm">Update</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm mt-4">

                            <tr>
                                <th>Whatsapp</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="whatsapp" value="{{ $data->whatsapp }}">
                                </td>
                            </tr>
                            <tr>
                                <th>GST No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="gst_no" value="{{ $data->gst_no }}">
                                </td>
                            </tr>
                            <tr>
                                <th>PAN No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="pan_card_no" value="{{ $data->pan_card_no }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Aadhaar No.</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="aadhaar_card_no" value="{{ $data->aadhaar_card_no }}">
                                </td>
                            </tr>

                            <tr>
                                <th>Referred By</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="referred_by" value="{{ $data->referred_by }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Nearest Airport</th>
                                <td>

                                    <select name="airport" id="airport" class="form-control form-control-sm">
                                        <option value="">Select Nearest Airport</option>
                                        @foreach($airports as $key => $value)
                                            <option value="{{ $value->id }}" @if($value->id == $data->nearest_airport) selected @endif>{{ $value->cityName }} - {{ $value->code }} - {{ $value->name }}</option>
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
                                            <option value="{{ $key  }}" @if($key==$data->account_manager_id) selected @endif>{{ $value}}</option>
                                        @endforeach
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="status" id="status" class="form-control form-control-sm" required>
                                        <option value="">Select State</option>
                                        <option value="1" @if(1==$data->status) selected @endif>Active</option>
                                        <option value="0" @if(0==$data->status) selected @endif>InActive</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Credit Requests Status</th>
                                <td>
                                    <select name="credit_request_status" id="credit_request_status" class="form-control form-control-sm" required>
                                        <option value="">Credit Requests Status</option>
                                        <option value="1" @if(1==$data->credit_request_status) selected @endif>Active</option>
                                        <option value="0" @if(0==$data->credit_request_status) selected @endif>InActive</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Has Api</th>
                                <td>
                                    <select name="has_api" id="has_api" class="form-control form-control-sm" required>
                                        <option value="">Has Api</option>
                                        <option value="1" @if(1==$data->has_api) selected @endif>Yes</option>
                                        <option value="0" @if(0==$data->has_api) selected @endif>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Agent Category</th>
                                <td>
                                    <select name="account_type_id" id="account_type_id" class="form-control form-control-sm" required>
                                        <option value="">Category</option>
                                        <option value="1" @if(1==$data->account_type_id) selected @endif>Category A</option>
                                        <option value="2" @if(2==$data->account_type_id) selected @endif>Category B</option>
                                        <option value="3" @if(3==$data->account_type_id) selected @endif>Category C</option>
                                        <option value="4" @if(4==$data->account_type_id) selected @endif>Category D</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="table-warning">
                                <th colspan="2" class="text-center text-uppercase">Verification</th>
                            </tr>

                            <tr>
                                <th>Pan Card Verification</th>
                                <td>
                                    <input type="checkbox" id="gridCheck1" name="isPANVerified" value="1" @if($data->isPANVerified) checked @endif>
                                </td>
                            </tr>
                            <tr>
                                <th>GST No Verification</th>
                                <td>

                                    <input  type="checkbox" id="gridCheck1" name="isGSTVerified"  @if($data->isGSTVerified) checked @endif value="1">

                                </td>
                            </tr>
                            <tr>
                                <th>Aadhaar No Verification</th>
                                <td>  <input  type="checkbox" id="gridCheck1" name="isAadhaarVerified" value="1" @if($data->isAadhaarVerified) checked @endif></td>
                            </tr>
                            <tr>
                                <th>Email Verification</th>
                                <td><input  type="checkbox" id="gridCheck1" name="isEmailVerified" @if($data->isEmailVerified) checked @endif value="1">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone Verification</th>
                                <td> <input  type="checkbox" id="gridCheck1" name="isPhoneVerified" @if($data->isPhoneVerified) checked @endif value="1"></td>
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
