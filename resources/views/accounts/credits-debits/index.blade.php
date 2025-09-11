 @extends('layouts.app')
 @section('title','Credits/Debits')
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
                     <h4 class="card-title text-uppercase">credits/debits</h4>
                     <p class="card-description">Credits and Debits in the Appication.</p>
                 </div>
                 <div class="col-md-6 text-right">
                     @can('show credit_limit_history')
                     <a href="{{ route('credits-debits.history') }}" class="btn btn-sm btn-primary">Credit History</a>
                     @endcan
                     <a href="{{ route('credits-debits.create') }}" class="btn btn-sm btn-success">New Credit / Debit </a>
                 </div>
             </div>
             <form action="">
                 <div class="row mb-3">
                    <div class="col-md-3">
                        <select name="agent_id" id="agent-select2" class="form-control select2">
                        @if($agent)
                            <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                        @endif
                            <option value="">Select Agent</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                         <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                    </div>
                    <!-- <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" id="start_date" name="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" placeholder="End Date" id="end_date" name="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
                    </div> -->
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-sm">Search</button>
                    </div>
                </div>
             </form>

             <div class="row">
                 <div class="table-sorter-wrapper col-lg-12 table-responsive ">
                     <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                         <thead class="thead-dark">
                             <tr>
                                 <th>#</th>
                                 <th>Ref. No</th>
                                 <th>Agent Name </th>
                                 <th>Type</th>
                                 <th>Amount</th>
                                 <th>Date & Time</th>
                                 <th>Desc</th>
                                 <th>User</th>
                                 {{-- <th width="10%">Action</th> --}}
                             </tr>
                         </thead>

                         <tbody>
                             @if ($data->count() > 0)
                                @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ $key + $data->firstItem() }}</td>
                                    <td>{{ $value->reference_no }}</td>
                                    <td>
                                        {{ $value->company_name }}
                                    </td>
                                    <td>
                                        @if ($value->type == 1)
                                        Temporary Credit
                                        @elseif($value->type == 5)
                                        Temporary Debit
                                        @elseif($value->type == 7)
                                        Distributor Balance
                                        @endif

                                    </td>
                                    <td>@money($value->amount) </td>
                                    <td>{{ $value->created_at->format('d-m-Y') }} {{ $value->created_at->format('h:i:s') }}
                                    </td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->name }} </td>

                                    {{-- <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('credit show')
                                            <a href="{{ route('credits-debits.show', $value->id) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                            @endcan
                                        </div>
                                    </td> --}}

                                </tr>
                                @endforeach
                             @else
                                <tr>
                                    <td colspan="8" class="text-center font-weight-bold">No Result Found</td>
                                </tr>
                            @endif


                         </tbody>
                             @if ($data->count() > 0)
                                {{ $data->appends(request()->except('page'))->links() }}
                              @endif

                         <tbody>

                         </tbody>
                     </table>


                 </div>
             </div>
         </div>
     </div>
 </div>

 @endsection
 @section('js')
 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

 <script>
    let start_date =   $('#start_date').val();
    let end_date   =   $('#end_date').val();
    if(!start_date && !end_date ){
        let today = new Date();
        let date1 = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        $('#end_date').val(date1);

        let today1       = new Date()
        let days         = 86400000
        let sevenDaysAgo = new Date(today1 - (30*days))
        let date2        = sevenDaysAgo.getDate()+'-'+(sevenDaysAgo.getMonth()+1)+'-'+sevenDaysAgo.getFullYear();
         $('#start_date').val(date2);
    }


     $(document).ready(function() {

         $('.select2').select2();

         $('#start_date').change(function() {
             let start_date = $('#start_date').val();
             let start_date_day = start_date.split('-')[0];
             let start_date_month =  start_date.split('-')[1];
             let start_date_year = start_date.split('-')[2];


             $( "#end_date" ).datepicker('destroy');
             $( "#end_date" ).datepicker({format:'dd-mm-yyyy', startDate:new Date(start_date_month+'-'+start_date_day+'-'+start_date_year) });
         })

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
        
         $('#payment_mode').change(function() {
             let val = $('#payment_mode').val();
             if (val == 2 || val == 3) {
                 $('.bankDiv').show();
             } else {
                 $('.bankDiv').val("");
                 $('.bankDiv').hide();
             }
         })
         $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/flight-tickets/ajax/search/agents',
                delay: 250 ,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                return query;
                },
                processResults: function (data) {
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
