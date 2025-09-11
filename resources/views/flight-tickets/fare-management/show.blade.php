@extends('layouts.app')
@section('title','Fare management')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Agent Details</h4>
                <p class="card-description">
                   Agent Details of <strong> {{ $data->company_name }}</strong>
                </p>

                <h6 class="mt-4 text-uppercase"> Agent Details</h6>
                <table class="table table-sm mt-4">
                    <tr>
                        <th>Company Name</th>
                        <td>{{ $data->company_name }}</td>
                        <th>Contact Name</th>
                        <td>{{ $data->contact_name }}</td>
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
                        <td>{{ $data->state->state }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $data->email }}</td>
                        <th>Phone</th>
                        <td>{{ $data->phone }}</td>
                        <th>Whatsapp</th>
                        <td>{{ $data->whatsapp }}</td>
                    </tr>
                </table>

                <h6 class="mt-4 text-uppercase">Other Information</h6>
                <table class="table table-sm mt-4">
                    <tr>
                        <th>GST NO</th>
                        <td>{{ $data->gst_no }}</td>
                        <th>City</th>
                        <td>{{ $data->airport }}</td>
                        <th>Referred By</th>
                        <td>{{ $data->referred_by }}</td>
                    </tr>
                    <tr>
                        <th>Monthly Business</th>
                        <td>{{ $data->monthly_business }}</td>
                        <th>Tavel Agent You Know</th>
                        <td>{{ $data->travel_agent_you_know }}</td>
                        <th>Credit Limit</th>
                        <td>{{ $data->credit_limit }}</td>
                    </tr>
                    <tr>
                        <th>Opening Balance</th>
                        <td>{{ $data->opening_balance }}</td>
                        <th>Account Manager</th>
                        <td><a href="{{ route('users.show',$data->account_manager_id) }}">{{ ucwords($data->account_manager->first_name) }} {{ $data->account_manager->last_name }} </a></td>
                        <th>Credit Agent</th>
                        <td>{{ $data->credit_agent }}</td>
                    </tr>
                    <tr>
                        <th>Account Type</th>
                        <td>{{ $data->account_type->name}}</td>
                        <th>Status</th>
                        <td>
                            @if($data->status == 1)
                                <div class="badge badge-success">Active</div>
                            @else
                                <div class="badge badge-danger">Inactive</div>
                            @endif
                        </td>

                    </tr>
                </table>
                </table>


            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
