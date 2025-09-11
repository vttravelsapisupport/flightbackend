@extends('layouts.app')
@section('title','OTPs')
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">OTPs</h4>
                    <p class="card-description">New OTPs in the Appication.</p>
                </div>
            </div>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>OTP</th>
                                <th>User</th>
                                <th>IP</th>
                                <th>Status</th>
                                <th>User Agent</th>
                                <th>Created AT</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($otps as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->otp }}</td>
                                <td>{{ $value->user->first_name }}</td>
                                <td>{{$value->ip}}</td>
                                <td>
                                    @if($value->difference > 600)
                                    <span class="badge badge-danger">Expired</span>
                                    @else
                                    <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td>{{$value->user_agents}}</td>
                                <td>
                                   {{$value->created_at}}
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
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
