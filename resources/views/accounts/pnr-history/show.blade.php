@extends('layouts.app')
@section('title','PNR History')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">PNR History Details</h4>
                </div>
            </div>
            <hr>
            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Payment Date</th>
                                <th>PNR</th>
                                <th>Passenger</th>
                                <th>Amount</th>
                                <th>Parent PNR</th>
                                <th>Airline Code </th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @foreach($datas as $key => $value)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{$value->payment_date}}</td>
                                <td>{{$value->pnr}}</td>
                                <td>{{$value->passenger_name}}</td>
                                <td>{{$value->amount}}</td>
                                <td>{{$value->parent_pnr}}</td>
                                <td>{{$value->airline_code}}</td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({});


        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $('#travel_date_from').change(function() {
            let from = $('#travel_date_from').val();
            $('#travel_date_to').val(from);
        })
        $('.btnDelete').click((e) => {
            let resp = confirm("Are you sure you want to delete the PurchaseEntry Entry Ticket ?")
            if (!resp) {
                e.preventDefault();
            }
        })
        $('#submitButton').click((e) => {
            e.preventDefault();
            let promptResp = confirm("Are you sure want to update");
            if (promptResp)
                $('#formsubmit').submit();
        })




    });
</script>
@endsection
