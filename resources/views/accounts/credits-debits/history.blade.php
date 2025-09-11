@extends('layouts.app')
@section('title','Credits/Debits')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
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
                        <h4 class="card-title text-uppercase">CREDIT LIMIT OR BALANCE HISTORY</h4>

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
                                <th>Date</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Action By</th>

                                <!-- <th width="10%">Action</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $k => $val)
                                    <tr>

                                        <th>{{ 1 +$k }}</th>
                                        <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d-m-y h:i:s') }}</td>
                                        <td>
                                            <ul>
                                                @foreach($val->old_values as $k => $v)
                                                    <li>   {{ $k }}   {{ $v }}  </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach($val->new_values as $key1 => $val1)
                                                    <li>   {{ $key1 }}   {{ $val1 }}  </li>
                                                @endforeach
                                            </ul>

                                        </td>
                                        <td>{{ $val->user }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

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


            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});

            $('.select2').select2();

            $('#start_date').change(function() {
                let start_date = $('#start_date').val();
                let start_date_day = start_date.split('-')[0];
                let start_date_month =  start_date.split('-')[1];
                let start_date_year = start_date.split('-')[2];


                $( "#end_date" ).datepicker('destroy');
                $( "#end_date" ).datepicker({format:'dd-mm-yyyy', startDate:new Date(start_date_month+'-'+start_date_day+'-'+start_date_year) });
            })
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
