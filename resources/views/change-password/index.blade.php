@extends('layouts.app')
@section('title','Change Password')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Change Account Password</h4>
                       
                    </div>
                    <div class="col-md-6 text-right">
                     
                    </div>
                </div>
                <form action="{{ route('change-password.store') }}" method="POST">
                        @CSRF
                        <div class="form-group">
                            <label for="exampleInputEmail1">Old Password</label>
                            <input type="password" class="form-control"  placeholder="Enter New Password" name="old_password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">New Password</label>
                            <input type="password" class="form-control"  placeholder="Enter New Password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm new Password" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
               
            </div>
        </div>
    </div>
   
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
