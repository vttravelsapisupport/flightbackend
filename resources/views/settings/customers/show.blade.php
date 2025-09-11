@extends('layouts.app')
@section('title','Agents/Distributors')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Agent / Distributor Details</h4>
            <div class="row">
                <div class="col-md-6">
                    Agents/Distributors Details of <strong> {{ $data->company_name }}</strong>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-sm btn-secondary" href="{{ route('agents.edit',$data->id) }}">
                        EDIT
                    </a>
                    <div class="btn-group">
                        <button class="btn-sm btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ACTION
                        </button>
                        <div class="dropdown-menu" style="margin-right: 2rem; position: absolute; transform: translate3d(-80px, 38px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">
                            <a href="{{ url('/agent/mail/welcome/'.$data->id) }}" class="dropdown-item">Send Welcome Email</a>
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Password Reset</button>
                        </div>
                    </div>

                </div>
            </div>
            <table class="table table-sm mt-4">
                <tr>
                    <th>Company Name</th>
                    <td>{{ $data->company_name }}</td>
                    <th>Contact Name</th>
                    <td>{{ $data->contact_name }}</td>
                    <th>Type</th>
                    <td>
                        @if($data->type == 1)
                        <span class="badge badge-pill badge-primary">Agent</span>
                        @else
                        <span class="badge badge-pill badge-primary">Distributor</span>
                        @endif
                    </td>
                </tr>
            </table>
            <h6 class="mt-4 text-uppercase">Contact Information</h6>
            <table class="table table-sm mt-4">
                <tr>
                    <th>Address</th>
                    <td>{{ $data->address }}</td>
                    <th>City</th>
                    <td>{{ $data->city }}</td>
                    <th>State</th>
                    <td> @if($data->state_id) {{ $data->state->name }}@else @endif</td>
                    <th></th>
                    <td> </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $data->email }}
                        @if($data->isEmailVerified)
                        <i class="mdi mdi-check-bold " style="font-size:20px;color:greenyellow"></i>
                        @elseif($data->email)
                        <i class="mdi mdi-alert-octagon" style="font-size:20px;color:rgb(253, 25, 25)"></i>
                        @else
                        @endif
                    </td>
                    <th>Phone</th>
                    <td>{{ $data->phone }}
                        @if($data->isPhoneVerified)
                        <i class="mdi mdi-check-bold " style="font-size:20px;color:greenyellow"></i>
                        @elseif($data->phone)
                        <i class="mdi mdi-alert-octagon" style="font-size:20px;color:rgb(253, 25, 25)"></i>
                        @endif
                    </td>
                    <th>Whatsapp</th>
                    <td>{{ $data->whatsapp }}</td>
                    <th>Nearest Airport</th>
                    <td>{{ $data->whatsapp }}</td>
                </tr>
            </table>

            <h6 class="mt-4 text-uppercase">Other Information</h6>
            <table class="table table-sm mt-4">
                <tr>
                    <th>GST NO</th>
                    <td>{{ $data->gst_no }}
                        @if($data->isGSTVerified)
                        <i class="mdi mdi-check-bold " style="font-size:20px;color:greenyellow"></i>
                        @elseif($data->gst_no)
                        <i class="mdi mdi-alert-octagon" style="font-size:20px;color:rgb(253, 25, 25)"></i>
                        @endif
                    </td>
                    <th>Aadhaar No.</th>
                    <td>{{ $data->aadhaar_card_no }}
                        @if($data->isAadhaarVerified)
                        <i class="mdi mdi-check-bold " style="font-size:20px;color:greenyellow"></i>
                        @elseif($data->aadhaar_card_no)
                        <i class="mdi mdi-alert-octagon" style="font-size:20px;color:rgb(253, 25, 25)"></i>
                        @endif
                    </td>
                    <th>Pan No.</th>
                    <td>{{ $data->pan_card_no }}
                        @if($data->isPANVerified)
                        <i class="mdi mdi-check-bold " style="font-size:20px;color:greenyellow"></i>
                        @elseif($data->pan_card_no)
                        <i class="mdi mdi-alert-octagon" style="font-size:20px;color:rgb(253, 25, 25)"></i>
                        @endif
                    </td>
                    <th>City</th>
                    <td>{{ $data->city }}</td>
                    <th>Referred By</th>
                    <td>{{ $data->referred_by }}</td>
                </tr>
                <tr>
                    <th>Opening Balance</th>
                    <td>{{ $data->opening_balance }}</td>
                    <th>Current Balance</th>
                    <td></td>
                    <th style="border-bottom: 1px solid #f2f2f2;">Credit Balance</th>
                    <td>{{ $data->credit_balance }}</td>
                    <th>Credit Limit</th>
                    <td>{{ $data->credit_limit}}</td>
                    <th>Credit Requests Status</th>
                    <td>
                        @if($data->credit_request_status == 1)
                            <div class="badge badge-success">Active</div>
                        @else
                        <div class="badge badge-danger">Inactive</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($data->status == 1)
                        <div class="badge badge-success">Active</div>
                        @else
                        <div class="badge badge-danger">Inactive</div>
                        @endif
                    </td>
                    <th>Agent Category</th>
                    <td>
                        @if($data->account_type_id == 1)
                            <span>Category A</span>
                        @elseif($data->account_type_id == 2)
                            <span>Category B</span>
                        @endif
                    </td>
                    <th>Remark</th>
                    <td>{{$data->remarks}}</td>
                    <th></th>
                    <td>
                       
                    </td>
                    <th></th>
                    <td></td>
                </tr>
            </table>
            <!-- verifiaction -->
            <h6 class="mt-4 text-uppercase">VERIFICATION details</h6>
            <table class="table table-sm mt-4">
                <tr>
                    <th>Pan Card Verification</th>
                    <td>
                        @if($data->isPANVerified == 1)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-danger">Not-Verified</span>
                        @endif
                    </td>
                    <th>GST No Verification</th>
                    <td>
                        @if($data->isGSTVerified == 1)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-danger">Not-Verified</span>
                        @endif
                    </td>
                    <th>Aadhaar No Verification</th>
                    <td>
                        @if($data->isAadhaarVerified == 1)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-danger">Not-Verified</span>
                        @endif
                    </td>
                    <th>Email Verification</th>
                    <td>
                        @if($data->isEmailVerified == 1)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-danger">Not-Verified</span>
                        @endif
                    </td>
                    <th>Phone Verification</th>
                    <td>
                        @if($data->isPhoneVerified == 1)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-danger">Not-Verified</span>
                        @endif
                    </td>
                </tr>
                <!-- <tr>
                    <th>Pan Card Attachment</th>
                    <td>
                        <a href="">Download</a>
                    </td>
                    <th>GST No Attachment</th>
                    <td></td>
                    <th>Aadhaar Attachment</th>
                    <td></td>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td></td>
                </tr> -->
            </table>
        </div>
    </div>
</div>
@can('show agent_change_history')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Change History</h4>
            <div class="table-responsive">
                <table class="table table-sm ">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Action By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $key => $val)
                        <tr>
                            <td>{{ 1 + $key }}</td>
                            <td>{{ $val->created_at->format('d-m-y h:i:s') }}</td>
                            <td>
                                <ul>
                                    @foreach($val->old_values as $k => $v)
                                    <li> {{ $k }} {{ $v }} </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    @foreach($val->new_values as $key1 => $val1)
                                    <li> {{ $key1 }} {{ $val1 }} </li>
                                    @endforeach
                                </ul>

                            </td>
                            <td>{{ $val->user->first_name }} {{ $val->user->last_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endcan
<div class="col-12 mt-3">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Booking History</h4>
            <div class="table-responsive">
                <table class="table table-sm ">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Bill No</th>
                            <th>Destination</th>
                            <th>PNR No.</th>
                            <th>Pax</th>
                            <th>Price</th>
                            <th>Infant</th>
                            <th>Inf. Price</th>
                            <th>Travel Date</th>
                            <th>DEPT</th>
                            <th>Airline</th>
                            <th>Flight No</th>
                            <th>Bokking Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-12 mt-3">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Additional Contacts</h4>
            <div class="table-responsive">
                <table class="table table-sm ">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Whatsapp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-12 my-3">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Additional Emails</h4>
            <div class="table-responsive">
                <table class="table table-sm ">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLongTitle">Change Account Password</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="col-md-12">
                   <form action="{{ route('agent.password.reset', $data->id) }}" method="POST">
                       @csrf
                       <div class="form-group">
                           <label for="old_password">Old Password</label>
                           <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" name="old_password" required>
                       </div>
                       <div class="form-group">
                           <label for="password">New Password</label>
                           <input type="password" class="form-control" id="password" placeholder="Enter New Password" name="password" required>
                       </div>
                       <div class="form-group">
                           <label for="password_confirmation">Confirm New Password</label>
                           <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm New Password" name="password_confirmation" required>
                       </div>
                       <button type="submit" class="btn btn-primary">Change Password</button>
                   </form>
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