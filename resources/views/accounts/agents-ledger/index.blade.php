@extends('layouts.app')
@section('title','Agent Ledger')
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

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="profile-tab1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Agent Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('supplier-ledger.index') }}">Supplier Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('distributor-ledger.index') }}">Distributor Ledger</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('supplier-ledger.api') }}">Api Vendor Ledger</a>
                </li>
            </ul>
            
           <div class="tab-content" id="myTabContent">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Agent Ledger</h4>
                        <p class="card-description">Agent’s Ledger in the Appication.</p>
                    </div>
                </div>
                <!-- agent ledger -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select required name="agent_id" id="agent-select2" class="form-control   form-control-sm select2">
                                    @if($agent)
                                    <option value="{{$agent->id}}" selected>{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                                    @endif
                                    <option value="">Select Agent</option>
                                </select>
                            </div>
                        <div class="col-md-2">
                                <input type="hidden" name="start_date" id="start_date">
                                <input type="hidden" name="end_date" id="end_date">
                                <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                        </div>
                            
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-sm" name="searchBtn">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="table-responsive table-sorter-wrapper col-lg-12">
                            <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order Type</th>
                                        <th>Ref. No</th>
                                        <th>Sector</th>
                                        <th>Travel Date</th>
                                        <th>Airline</th>
                                        <th>PNR</th>
                                        <th>No Of Pax</th>
                                        <th>Pax Name</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        <th>Desc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $value)
                                    <tr>
                                        <td>{{  1 + $key}}</td>
                                        <td>{{  Carbon\Carbon::parse($value->created_at)->format('d-m-Y h:i:s') }}</td>
                                        <td>
                                            @if ($value->type == 1)
                                                Temp Credit
                                            @elseif($value->type == 2)
                                                Air Ticket
                                            @elseif($value->type == 3 )
                                                Receipt
                                            @elseif($value->type == 4)
                                                Refund
                                            @elseif($value->type == 5)
                                                Temp Debit
                                            @elseif($value->type == 6)
                                                Additional Services
                                            @elseif($value->type == 7)
                                                Distributor Balance
                                            @elseif($value->type == 8)
                                                Distributor Debit
                                            @elseif($value->type == 9)
                                                Credit shell
                                            @elseif($value->type == 10)
                                            Debit
                                            @elseif($value->type == 11)
                                                Credit
                                            @endif

                                        </td>

                                        <td>
                                            {{ $value->reference_no }}
                                        </td>
                                        @if($value->type == 2 || $value->type == 4 || $value->type == 6 || $value->type == 9)
                                            <td>
                                                {{ $value->src }}-{{ $value->dest }}
                                            </td>
                                            <td>
                                                {{  Carbon\Carbon::parse($value->travel_date)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                {{ $value->airline }}
                                            </td>
                                            <td>
                                                {{ $value->pnr }}
                                            </td>
                                            <td>
                                                @if($value->type == 4)
                                                    {{ $value->refund_pax_count }}
                                                @else
                                                    {{ $value->pax_count }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->type == 4)
                                                {{ $value->first_element }}  @if($value->refund_pax_count > 1) +  {{$value->refund_pax_count - 1 }} @endif
                                                @else
                                                {{ $value->pax_name }} @if($value->pax_count > 1) +  {{$value->pax_count - 1 }} @endif
                                                @endif
                                            </td>
                                            @elseif( $value->type == 1 )
                                                    <td colspan="6" class="text-center  font-weight-bold text-success">
                                                        Temp Credit ( {{ $value->amount }} )
                                                    </td>
                                            @elseif( $value->type == 5 )
                                                    <td colspan="6" class="text-center  font-weight-bold text-success">
                                                        Temp Debit ( {{ $value->amount }} )
                                                    </td>
                                            @elseif($value->type == 3 ||   $value->type == 10)
                                                <td colspan="6">

                                                </td>
                                            @endif
                                        <td>
                                            @if ($value->type == 2 || $value->type == 6 || $value->type == 8 || $value->type == 10)
                                            {{ $value->amount }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($value->type == 3 || $value->type == 4 || $value->type == 7  || $value->type == 9  || $value->type == 11  )
                                                {{ $value->amount }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $value->balance }}
                                        </td>
                                        <td>
                                            {{ $value->remarks }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <th colspan="12" class="text-center">No Result Found</th>
                                    </tr>
                                    @endif
                                    </td>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // let start_date = $('#start_date').val();
        // let end_date = $('#end_date').val();
        // if (!start_date && !end_date) {
        //     let today = new Date();
        //     let date1 = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
        //     $('#end_date').val(date1);

        //     let today1 = new Date()
        //     let days = 86400000
        //     let sevenDaysAgo = new Date(today1 - (30 * days))
        //     let date2 = sevenDaysAgo.getDate() + '-' + (sevenDaysAgo.getMonth() + 1) + '-' + sevenDaysAgo.getFullYear();
        //     $('#start_date').val(date2);
        // }
        
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
            $('#dates').val('Date Range')

        @endif

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            let start_date = picker.startDate.format('DD-MM-YYYY');
            let end_date = picker.endDate.format('DD-MM-YYYY');
            // Update hidden fields
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
        });
       
        $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/flight-tickets/ajax/search/agents',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },

                dataType: 'json',
                cache: true
            },
            minimumInputLength: 4,
        });
    });
</script>
@endsection
