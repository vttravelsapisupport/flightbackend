@extends('layouts.app')
@section('title','Manage PNR History')
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
                    <h4 class="card-title text-uppercase">Manage PNR History</h4>
                    <p class="card-description">PNR history (Including Parent PNR Details).</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control  form-control-sm  datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
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
                                <th>ID</th>
                                <th>Is Online</th>
                                <th>Destination ID</th>
                                <th>Airline ID</th>
                                <th>Is Domestic</th>
                                <th>PNR</th>
                                <th>Flight Number</th>
                                <th>Price Type</th>
                                <th>Travel Date</th>
                                <th>Name List</th>
                                <th>Name List Day</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th>Sold</th>
                                <th>Blocks</th>
                                <th>Cost Price</th>
                                <th>Sell Price</th>
                                <th>Markup Price</th>
                                <th>Owner ID</th>
                                <th>Purchase Entry ID</th>
                                <th>Flight Route</th>
                                <th>Name List Status</th>
                                <th>Arrival Date</th>
                                <th>Base Price</th>
                                <th>Tax</th>
                                <th>Infant</th>
                                <th>Child</th>
                                <th>Name List Hour</th>
                                <th>Name List Timestamp</th>
                                <th>Infant Count</th>
                                <th>Is Refundable</th>
                                <th>Segments</th>
                                <th>Created AT</th>
                                <th>Updated AT</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @if(!empty($data['PnrHistory']))
                            @foreach($data['PnrHistory'] as $key => $val)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ $val->ID }}</td>
                                <td>{{ $val->IsOnline }}</td>
                                <td>{{ $val->DestinationID }}</td>
                                <td>{{ $val->AirlineID }}</td>
                                <td>{{ $val->IsDomestic }}</td>
                                <td>{{ $val->PNR }}</td>
                                <td>{{ $val->FlightNumber }}</td>
                                <td>{{ $val->PriceType }}</td>
                                <td>{{ $val->TravelDate }}</td>
                                <td>{{ $val->NameList }}</td>
                                <td>{{ $val->NameListDay }}</td>
                                <td>{{ $val->DepartureTime }}</td>
                                <td>{{ $val->ArrivalTime }}</td>
                                <td>{{ $val->Quantity }}</td>
                                <td>{{ $val->Available }}</td>
                                <td>{{ $val->Sold }}</td>
                                <td>{{ $val->Blocks }}</td>
                                <td>{{ $val->CostPrice }}</td>
                                <td>{{ $val->SellPrice }}</td>
                                <td>{{ $val->MarkupPrice }}</td>
                                <td>{{ $val->OwnerID }}</td>
                                <td>{{ $val->PurchaseEntryID }}</td>
                                <td>{{ $val->FlightRoute }}</td>
                                <td>{{ $val->NameListStatus }}</td>
                                <td>{{ $val->ArrivalDate }}</td>
                                <td>{{ $val->BasePrice }}</td>
                                <td>{{ $val->Tax }}</td>
                                <td>{{ $val->Infant }}</td>
                                <td>{{ $val->Child }}</td>
                                <td>{{ $val->NameListHour }}</td>
                                <td>{{ $val->NameListTimestamp }}</td>
                                <td>{{ $val->InfantCount }}</td>
                                <td>{{ $val->IsRefundable }}</td>
                                <td>{{ $val->Segments }}</td>
                                <td>{{ $val->CreatedAT }}</td>
                                <td>{{ $val->UpdatedAT }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="text-align: left; padding: 10px 15px 10px;" colspan="37">Nothing to show</td>
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

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        if (!start_date && !end_date) {
            let today = new Date();
            let date1 = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
            $('#end_date').val(date1);

            let today1 = new Date()
            let days = 86400000
            let sevenDaysAgo = new Date(today1 - (30 * days))
            let date2 = sevenDaysAgo.getFullYear() + '-' + (sevenDaysAgo.getMonth() + 1) + '-' + sevenDaysAgo.getDate();
            $('#start_date').val(date2);
        }

        $(".datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#start_date').change(function() {
            let start_date = $('#start_date').val();
            let start_date_year = start_date.split('-')[0];
            let start_date_month = start_date.split('-')[1];
            let start_date_day = start_date.split('-')[2];

            let endDate = $('#end_date');
            endDate.datepicker('destroy');
            endDate.datepicker({
                format: 'yyyy-mm-dd',
                startDate: new Date(start_date_year + '-' + start_date_month + '-' + start_date_day)
            });
            endDate.val(start_date);
            endDate.attr("required", "true");
        })
    });
</script>
@endsection