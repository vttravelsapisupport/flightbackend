@extends('layouts.app')
@section('title','Manage Refunds')
@section('css')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Manage Duplicate Booking Details</h4>
                    <p class="card-description">List of duplicate booking details entries (Soft deleted items are excluded).</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="bill_no" placeholder="Bill No" value="{{ request()->query('bill_no') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>
            <hr>
            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Book Ticket ID</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Book Ticket Detail IDs</th>
                                <th>Created AT</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @if(!empty($data['BookingDetails']))
                            @foreach($data['BookingDetails'] as $key => $val)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ $val->BookTicketID }}</td>
                                <td>{{ $val->Title }}</td>
                                <td>{{ $val->FirstName }}</td>
                                <td>{{ $val->LastName }}</td>
                                <td>{{ $val->BTD_IDS }}</td>
                                <td>{{ $val->CreatedAT }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="text-align: left; padding: 10px 15px 10px;" colspan="10">Nothing to show</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection