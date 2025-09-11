@extends('layouts.app')
@section('title','Change Agnets Password')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Change agents Password</h4>

                    </div>

                </div>
                <form action="{{ route('change-password-api-agents.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email Id / Phone no</label>
                        <input type="text" class="form-control" placeholder="Enter Email or Phone" name="email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" class="form-control" placeholder="Enter New Password" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>

                @if(session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
