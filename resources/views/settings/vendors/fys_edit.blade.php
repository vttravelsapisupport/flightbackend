@extends('layouts.app')
@section('title','Vendors')
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Vendor FY opening balance update</h4>
                <p class="card-description">

                </p>

                <form class="forms-sample" method="POST" action="{{ route('vendors.FysOpeningBalanceUpdate',$opening_balances->id) }}">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Vendor name" name="amount" value="{{$opening_balances->amount}}">
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
