@extends('layouts.app')
@section('title','Roles')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Role Details</h4>
                <p class="card-description">
                   Role Details of <strong> {{ $details->name }}</strong>
                </p>


                <div class="row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control-plaintext" id="name" name="name" value="{{ $details->name }}">
                    </div>
                </div>
                <hr>
                <h6 class="text-uppercase">Permissions</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rolePermissions as $key => $value)
                        <tr>
                            <td> {{ 1+ $key }}</td>
                            <td> {{ $value->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>




            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
