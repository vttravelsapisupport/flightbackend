@extends('layouts.app')
@section('title','Flight Inventory Summery')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
                    <h4 class="card-title text-uppercase">Flight Inventory Summery</h4>
                    <p class="card-description">Flight Inventory Summery in the Appication.</p>
                </div>
            </div>
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Start Date Range" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                    </div>
                   
                    <div class="col-md-2">
                        <select multiple name="own_vendor_id[]" id="own_vendor_id" class="form-control form-control-sm select2">
                            <option value="">Select Own Party</option>
                            @if(isset($own_vendors))
                            @foreach($own_vendors as $id => $name)
                            <option value="{{$id}}" @if(request()->query('own_vendor_id'))
                                @if(in_array($id,request()->query('own_vendor_id'))) selected @endif
                                @endif
                                >
                                {{$name}}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select multiple name="third_party_vendor_id[]" id="third_party_vendor_id" class="form-control form-control-sm select2">
                            <option value="">Select Third Party Vendor</option>
                            @if(isset($third_party_vendors))
                            @foreach($third_party_vendors as $id => $name)
                            <option value="{{$id}}" @if(request()->query('third_party_vendor_id'))
                                @if(in_array($id,request()->query('third_party_vendor_id'))) selected @endif
                                @endif
                                >
                                {{$name}}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="manager_id" id="manager_id" class="form-control form-control-sm select2">
                            <option value="">Select User</option>
                            @if(isset($users))
                            @foreach($users as $id => $value)
                            <option value="{{$value->id}}" @if(request()->query('manager_id') == $value->id) selected @endif>
                                {{$value->first_name}} {{$value->last_name}}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" name="exclude_zero" id="exclude_zero" @if(request()->query('exclude_zero') == 1) checked @endif
                        value="1">
                        Exclude Zero
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" name="unassigned_sector" id="unassigned_sector" value="1" @if(request()->query('unassigned_sector') == 1) checked @endif>
                        Unassigned Sector
                    </div>
                    <!-- <div class="col-md-2">
                       <input type="checkbox" name="unassigned_sector" id="unassigned_sector" value="2">
                       Unassigned sector
                    </div> -->
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                    </div>
                </div>
            </form>
            @if(isset($details))
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Source</th>
                                <th>Sector</th>
                                <th>Revenue Manager</th>
                                <th>Airline</th>
                                <th>Flight Number</th>
                                <th>Quantity</th>
                                <th>Sold</th>
                                <th>Inventory</th>
                                <th>Blocked</th>

                            </tr>

                        </thead>
                        <tbody class="text-center">

                            @foreach($details as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->owner->name }} </td>
                                <td>{{ $value->destination->name }} </td>
                                <td>{{ $value->first_name ? $value->first_name. ' '. $value->last_name : 'Undecided' }}</td>
                                <td>{{ $value->airline->name }}</td>
                                <td>{{ $value->flight_no }}</td>
                                <td>{{ $value->total_quantity }}</td>
                                <td>{{ $value->total_sold }}</td>
                                <td>{{ $value->total_available }}</td>
                                <td>{{ $value->total_block }}</td>

                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                    <div> {{ $details->appends(request()->except('page'))->links() }}</div>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        // let start_date = $('#start_date').val();
        // let end_date = $('#end_date').val();
        // if (!start_date && !end_date) {
        //     let today = new Date();
        //     let date1 = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
        //     $('#start_date').val(date1);

        //     let today1 = new Date()
        //     today1.setDate(today1.getDate() + 30);
        //     let date2 = today1.getDate() + '-' + (today1.getMonth() + 1) + '-' + today1.getFullYear();
        //     $('#end_date').val(date2);
        // }

        // $(".datepicker").datepicker({
        //     todayHighlight: true,
        //     autoclose: true,
        //     format: 'dd-mm-yyyy'
        // });
        $('.select2').select2({});
        @if(request()->query('start_date'))
            $('#dates').daterangepicker({
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
            let start_date = '{!! request()->query('start_date') !!}';
            let end_date = '{!! request()->query('end_date') !!}';
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
            @else
                $('#dates').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                showDropdowns: true,
                locale: {
                    "format": "DD-MM-YYYY",
                }
            });
        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let start_date = picker.startDate.format('DD-MM-YYYY');
            let end_date = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
        });

        // $('#start_date').change(function() {
        //     let start_date = $('#start_date').val();
        //     let start_date_day = start_date.split('-')[0];
        //     let start_date_month = start_date.split('-')[1];
        //     let start_date_year = start_date.split('-')[2];

        //     let endDate = $('#end_date');
        //     endDate.datepicker('destroy');
        //     endDate.datepicker({
        //         format: 'dd-mm-yyyy',
        //         startDate: new Date(start_date_month + '-' + start_date_day + '-' + start_date_year)
        //     });
        //     endDate.val(start_date);
        //     endDate.attr("required", "true");
        // })

        // function get_end_date() {
        //     let start_date_val = $('#start_date').val();
        //
        //     if(!start_date_val){
        //         let today = new Date();
        //         let new_end_date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        //         $('#end_date').val(date1);
        //
        //         let today1       = new Date()
        //         let days         = 86400000
        //         let sevenDaysAgo = new Date(today1 - (30*days))
        //         let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
        //         $('#start_date').val(date2);
        //     }
        // }
    });
</script>
@endsection