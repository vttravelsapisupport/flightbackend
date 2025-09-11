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
                            <a href="{{ url('/agent/mail/welcome/'.$data->id) }}" class="btn btn-primary btn-sm btn-primary">Send Welcome Email</a>
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
                        <th>Status</th>
                        <td>
                            @if($data->status == 1)
                                <div class="badge badge-success">Active</div>
                            @else
                                <div class="badge badge-danger">Inactive</div>
                            @endif
                        </td>
                        <th></th>
                        <th></th>
                    </tr>
                </table>


            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
