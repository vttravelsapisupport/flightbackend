@extends('layouts.app')
@section('title','Permissions')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase"> Edit Permission Detail</h4>
                <p class="card-description">
                   Edit Permission Detail
                </p>

                <form class="forms-sample" method="POST" action="{{ route('permissions.update',$details->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Permission" name="name" value="{{ $details->name }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
@endsection
