@extends('layouts.app')
@section('title','PNR Name List')
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
                        <h4 class="card-title text-uppercase">PNR Name List</h4>
                        <p class="card-description">Passenger Name Record Listed Tickets in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        @can('namelist export')
                        <a href="{{ route('pnr-name-list.export').'?' . http_build_query([
                                        'destination_id' => request()->query('destination_id'),
                                        'name_list' => request()->query('name_list'),
                                        'travel_date' => request()->query('travel_date'),
                                        'airline' => request()->query('airline'),
                                        'pnr_no' => request()->query('pnr_no'),
                                        ])   }}" class="btn btn-sm btn-primary" >Export Excel</a>
                        @endcan
                    </div>
                </div>
                <form class="forms-sample row" method="GET" action="">
                    <div class="col-md-3">
                        <select name="destination_id" id="destination_id" class="form-control form-control-sm destination select2">
                            <option value="">Select Destination</option>
                            @foreach($destinations as $key => $value)
                                <option value="{{ $value->id }}" @if($value->id == request()->query('destination_id')) selected @endif>{{ ucwords($value->name) }}  {{ $value->code   }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" name="name_list" placeholder="Enter the NameList Date" autocomplete="off" value="{{ request()->query('name_list') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm  datepicker" name="travel_date" placeholder="Enter the Travel Date" autocomplete="off" value="{{ request()->query('travel_date') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="airline" id="airline" class="form-control form-control-sm airline">
                            <option value="">Select Airline</option>
                            @foreach($airlines as $key => $value)
                                <option value="{{ $key }}"  @if($key == request()->query('airline')) selected @endif>{{ ucwords($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-behance btn-block btn-sm" name="search" value="search"> Search</button>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="pnr_no" placeholder="Enter the PNR No" autocomplete="off" value="{{ request()->query('pnr_no') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="1" name="exclude_zero" @if (request()->query('exclude_zero') !== null) checked @endif>
                                Exclude Zero
                                <i class="input-helper"></i></label>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th >Airline</th>
                                    <th >Destination</th>
                                    <th >PNR No.</th>
                                    <th>Qty</th>
                                    <th >Block</th>
                                    <th>Avlb</th>
                                    <th>Travel Date</th>
                                    <th>DPT</th>
                                    <th>ARV</th>
                                    <th>Owner</th>
                                    <th>Name List </th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            @foreach($data as $key => $value)
                            <tr  class=" @if ($value->namelist_status == 1) table-info @elseif($value->namelist_status == 2) table-warning @elseif($value->namelist_status == 3) table-danger
                            @elseif($value->namelist_status == 4)
                                table-primary
                            @elseif($value->namelist_status == 5)
                                table-secondary
                            @endif">
                                <td>{{ 1 +$key }}</td>
                                <td>{{ $value->airline_name }} <br>
                                    @if($value->seat_live_count > 0 ) <span class="badge badge-danger">Seat Live count {{$value->seat_live_count}}</span>@endif
                                </td>
                                <td>{{ $value->destination_name }} </td>
                                <td>{{ $value->pnr }}</td>
                                <td>{{ $value->quantity }}</td>
                                <td>{{ $value->blocks }}</td>
                                <td>{{ $value->available }}</td>
                                <td>{{ $value->travel_date->format('d-M-y') }}</td>
                                <td>{{ $value->departure_time }}</td>
                                <td>{{ $value->arrival_time }}</td>
                                <td @if($value->is_third_party == 1) class="bg-warning font-weight-bold" @endif title="@if($value->is_third_party == 1)Third Party Vendor  @endif"> {{ ucwords($value->owner_name) }}</td>
                                <td>{{ $value->name_list->format('d-M-y') }}</td>
                                <td>
                                    <a href="{{ route('pnr-name-list.show',$value->id) }}" class="btn  btn-sm btn-info" target="_blank">View</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $data->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
          $('.select2').select2({

});
$(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
    </script>
@endsection
