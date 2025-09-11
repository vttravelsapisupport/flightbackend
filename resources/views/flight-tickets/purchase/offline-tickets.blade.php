@extends('layouts.app')
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
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Ticket Purchase Entry</h4>
            <hr>
            <h6>{{ $current_date }}</h6>
            <form class="forms-sample mb-2" method="POST" action="{{ url('flight-tickets/purchase/offline') }}">
                @csrf
                <input type="hidden" name="isForm" value="1">
                <button class="btn btn-sm btn-primary">Offline</button>
            </form>
            <table class="table table-sm">
                <tr>
                    <th>#</th>
                    <th>PNR NO</th>
                    <th>Available Qty</th>

                    <th>Vendor</th>
                    <th>Travel Date and Time</th>
                    <th>Deactive Before</th>
                    <th>Name List Date</th>
                    <th>Calculated</th>
                    <th>Status</th>
                    <th>Deactivate On</th>
                </tr>
                @foreach($datas as $key => $data)
                    <tr>
                        <td>{{ 1 +$key }}</td>
                        <td><a href="{{ route('purchase-entry.show',$data->id) }}">{{ $data->pnr  }}</a></td>

                        <td>{{ $data->available }}</td>
                        <td>{{ $data->owner->name  }}</td>
                        <td>{{ $data->travel_date->format('d-m-Y') }} {{ $data->departure_time }}</td>
                        <td>{{ $data->name_list_hour }} hrs</td>
                        <td>{{ $data->name_list->format('d-m-Y')  }}</td>
                        <td>{{ $data->namelist_timestamp }}</td>
                        <td> @if($data->namelist_timestamp_expired == 1) active @else Expired @endif</td>
                        <td>{{ $data->name_list_timestamp->format('d-m-Y h:i:s')  }}</td>
                    </tr>
                @endforeach
            </table>
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
        $('.select2').select2();
        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $('.timepicker').datetimepicker({
            format: 'HH:mm',
        });


        $('#base_price,#tax,#markup_price').change(function() {
            let base_price = $('#base_price').val();
            let tax = $('#tax').val();
            let markup_price = $('#markup_price').val();
            let total = parseFloat(base_price) + parseFloat(tax);
            $('#cost_price').val(total);
            $('#child').val(parseFloat(total) + parseFloat(markup_price));
        });


        $('#name_list_day,#travel_date').change(function() {
            var travel_date = $("#travel_date").datepicker('getDate');
            var arrival_date = $("#arrival_date").datepicker('getDate');
            $('#arrival_date').datepicker("update", formatDate(travel_date));
            var name_list_day = $("#name_list_day").val();
            if (name_list_day && travel_date) {
                var name_list_date = addDays(travel_date, name_list_day);
                $('#name_list_date').datepicker("update", name_list_date);
                // $('#name_list_date').val(name_list_date);
            }
        })

        function addDays(date, days) {
            var result = date;
            result.setDate(result.getDate() - days);
            return formatDate(result);
        }

        function formatDate(date) {
            var monthNames = [
                "1", "2", "3",
                "4", "5", "6", "7",
                "8", "9", "10",
                "11", "12"
            ];

            var day = date.getDate();
            console.log(day);
            var monthIndex = date.getMonth();
            var year = date.getFullYear();

            return day + '-' + monthNames[monthIndex] + '-' + year;
        }



    });
</script>

@endsection
