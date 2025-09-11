@extends('layouts.app')
@section('title','Debitor List')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">


@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Debitor List</h4>
                    <p class="card-description">Debitor’s List in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" id="excelDownload" type="button">
                        <i class="mdi mdi-file-excel"></i>Export Excel
                    </button>
                </div>
            </div>
            <br>
            <form action="">
                <div class="row mb-3" v-if="searchAgentResult">

                    <div class="col-md-2">
                        <select name="agent_id" id="agent-select2" class="form-control form-control-sm destination select2">
                            <option value="">Select Agents</option>
                            @if($agent)
                            <option value="{{$agent->id}}" selected
                            >{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="airport" id="airport" class="form-control form-control-sm select2 ">
                            <option value="">Select Airport</option>
                            @foreach($airports as $k => $v)
                            <option value="{{ $v->id }}"  @if($v->id == request()->query('airport')) selected @endif>{{ $v->code }} {{ $v->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                       <input type="text" class="form-control  form-control-sm" name="city" placeholder="Enter the city name" value="{{ request()->query('city') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="text" class="form-control form-control-sm" id="dates" placeholder="Payment Date" value="{{ request()->query('start_date') }} - {{ request()->query('end_date') }}">
                    </div>

                    <!-- <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm " name="date_from" autocomplete="off"  value="{{ request()->query('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm " name="date_to" autocomplete="off"  value="{{ request()->query('date_to') }}">
                    </div> -->

                    <div class="col-md-2">
                    <select name="account_manager_id" id="account_manager_id" class="form-control form-control-sm select2 ">
                            <option value="">Select Manager</option>
                            @foreach($sale_rep as $k => $v)
                            <option value="{{ $v->id }}" @if($v->id == request()->query('account_manager_id')) selected @endif>{{ $v->first_name }} {{ $v->last_name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-2">
                            <select name="result" id="result" class="form-control form-control-sm  select2">
                                <option value="">No. of Result</option>
                                <option value="200" @if ("200" == request()->query('result')) selected @endif>200</option>
                                <option value="300" @if ("300" == request()->query('result')) selected @endif>300</option>
                                <option value="500" @if ("500" == request()->query('result')) selected @endif>500</option>
                                <option value="1000" @if ("1000" == request()->query('result')) selected @endif>1000</option>
                                <option value="5000" @if ("5000" == request()->query('result')) selected @endif>5000</option>
                                <option value="10000" @if ("10000" == request()->query('result')) selected @endif>10000</option>
                                <option value="100000" @if ("100000" == request()->query('result')) selected @endif>100000</option>
                            </select>
                    </div>

                    <div class="col-md-1 mt-2">
                        <input type="checkbox" name="exclude_zero" value="1"  @if (1 == request()->query('exclude_zero')) checked @endif />
                        Exclude Zero
                    </div>
                    <div class="col-md-1 mt-2">
                        <input type="checkbox" name="exclude_positive" value="1"  @if (1 == request()->query('exclude_positive')) checked @endif />
                        Exclude Positive
                    </div>
                    <div class="col-md-2  mt-2">
                        <input type="checkbox" name="exclude_negative" value="1"  @if (1 == request()->query('exclude_negative')) checked @endif />
                        Exclude Negative
                    </div>
                    <div class="col-md-1  mt-2">
                        <button class="btn btn-primary btn-sm">Search</button>
                    </div>

                </div>
            </form>

            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th class="sortStyle">#  <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Agent Code <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Agency name and  phone <i class="mdi mdi-chevron-down"></i></th>

                                <th class="sortStyle">Representative <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Actual balance  <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Credit balance <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Credit Limit <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Unflow Amount <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Remarks <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Airport <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">City <i class="mdi mdi-chevron-down"></i></th>
                                <th class="sortStyle">Last Booking date <i class="mdi mdi-chevron-down"></i></th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $v)
                            @php
                            $account_manager = $v->account_manager;
                            $remark_agent = ($v->getAgentDebitorRemark) ? $v->getAgentDebitorRemark->user : '';
                            $unflow_amount = 0;
                            foreach($v->getUnflowAmount as $a => $b){
                            $unflow_amount += ($b->adults * $b->pax_price) + ($b->child * $b->child_charge) + ($b->infants * $b->infant_charge);


                            }
                            $agent_start_date = Carbon\Carbon::now()->format('d-m-Y');
                                $agent_end_date = Carbon\Carbon::now()->subDays(30)->format('d-m-Y');
                            @endphp
                            <tr>
                                <td>{{ 1 + $k }}</td>

                                <td>
                                   <a href="{{ route('agents.show',$v->id) }}" target="_blank"> {{ $v->code }} </a>
                                </td>
                                <td>
                                <a href="{{ route('agent-ledger.index',[
                                        'agent_id' => $v->id,
                                        'start_date' => $agent_end_date,
                                        'end_date' => $agent_start_date
                                        ]) }}"
                                        target="__blank"
                                        > {{ $v->company_name }}</a>
                                        <br> {{ $v->phone }}</td>
                                <td>
                                    @if($v->account_manager_id)
                                    {{ $account_manager->first_name }}
                                    {{ $account_manager->last_name }}
                                    @endif
                                </td>
                                <td>
                                    @money($v->opening_balance_bkp)
                                    <span class="tooltip-actual-balance" title='Please wait..' data-id='{{$v->id}}' id='agent_{{$v->id}}'>
                                        <i class="mdi mdi-information-outline mdi-18px"></i>
                                    </span>


                                </td>
                                <td>
                                    @money($v->credit_balance_bkp)
                                    <span class="tooltip-credit-balance" title='Please wait..' data-id='{{$v->id}}' id='credit_balance_{{$v->id}}'>
                                        <i class="mdi mdi-information-outline mdi-18px"></i>
                                    </span>

                                </td>
                                <td>
                                    @money($v->credit_limit_bkp)
                                    <span class="tooltip-credit-limit-transaction" title='Please wait..' data-id='{{$v->id}}' id='credit_limit_{{$v->id}}'>
                                        <i class="mdi mdi-information-outline mdi-18px"></i>
                                    </span>
                                </td>
                                <td>
                                    @money($unflow_amount)
                                    <span class="tooltip-unflow-transaction" title='Please wait..' data-id='{{$v->id}}' id='unflown_{{$v->id}}'>
                                        <i class="mdi mdi-information-outline mdi-18px"></i>
                                    </span>
                                </td>
                                <td>
                                    @if($remark_agent)
                                    {{ $v->getAgentDebitorRemark->remarks }}
                                    @endif
                                    <br>
                                    @if($remark_agent)
                                    <small>
                                        {{ $remark_agent->first_name }} {{ $remark_agent->last_name }}
                                        <br>
                                        {{ $v->getAgentDebitorRemark->created_at->format('d-m-Y h:i:s')}}
                                    </small>
                                    <span class="tooltip-remarks" title='Please wait..' data-id='{{$v->id}}' id='remarks_{{$v->id}}'>
                                        <i class="mdi mdi-information-outline mdi-18px"></i>

                                        @endif
                                        <a href="{{ route('debitor-remarks.create',['agent_id'=> $v->id]) }}" target="_blank"> <i class="mdi mdi-plus-circle"></i></a>
                                </td>
                                <td>
                                    @if($v->nearestAirportDetails)
                                    {{ $v->nearestAirportDetails->code }}
                                    @endif
                                </td>
                                <td>
                                    {{ $v->city }}
                                </td>
                                <td>
                                    @if($v->getLatestBooking)
                                    {{ $v->getLatestBooking->created_at->format('d-m-Y h:i:s')}}
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <pagination :data="paginationAgent" @pagination-change-page="getAgents"></pagination>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>

<script src="{{  asset('assets/js/jq.tablesort.js') }}"></script>
<script>
    $(document).ready(function() {
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

    @if(request()->query('start_date'))
        $('#dates').daterangepicker({
            showDropdowns: true,
            locale: {
                "format": "DD-MM-YYYY",
            }
        });
       
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

    $('.select2').select2({});

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
<script>
        let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-debitor-list.csv";
        var showError = true;
        $("#excelDownload").click(function () {
            if ($('#sortable-table-2 tbody').find('tr').length == 0) {
                if (showError) {
                    $('#app').prepend(
                        $('<div/>')
                            .attr("role", "alert")
                            .addClass("alert alert-danger")
                            .text("Cannot export an empty data sheet.")
                    );
                    showError = false;
                }
            } else {
                $("#sortable-table-2").first().table2csv({
                    filename: filename,
                });
            }
        });
</script>
<script>
     if ($('#sortable-table-2').length) {
                $('#sortable-table-2').tablesort();
            }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.select2').select2();
    $("#agent-select2").select2({
        allowClear: false,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.html;
        },
        templateSelection: function(data) {
            return data.text;
        },
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
</script>
<script>
    $(document).on("click", ".tooltip-actual-balance", function() {

        $(this).tooltip({
            items: ".tooltip-actual-balance",
            open: function(event, ui) {
                var id = this.id;
                var agent_id = $(this).attr('data-id');

                $.ajax({
                    url: '/flight-tickets/ajax/agent-booking-transaction',
                    type: 'GET',
                    data: {
                        agent_id: agent_id,

                    },
                    success: function(response) {
                        // Setting content option
                        $("#" + id).tooltip('option', 'content', response);
                    }
                });
            },
            close: function(event, ui) {
                var me = this;
                ui.tooltip.hover(
                    function() {
                        $(this).stop(true).fadeTo(400, 1);
                    },
                    function() {
                        $(this).fadeOut("400", function() {
                            $(this).remove();
                        });
                    }
                );
                ui.tooltip.on("remove", function() {
                    $(me).tooltip("destroy");
                });
            },
        });
        $(this).tooltip("open");
    });
    $(document).on("click", ".tooltip-credit-balance", function() {

        $(this).tooltip({
            items: ".tooltip-credit-balance",
            open: function(event, ui) {
                var id = this.id;
                var agent_id = $(this).attr('data-id');

                $.ajax({
                    url: '/flight-tickets/ajax/agent-credit-transaction',
                    type: 'GET',
                    data: {
                        agent_id: agent_id,

                    },
                    success: function(response) {
                        // Setting content option
                        $("#" + id).tooltip('option', 'content', response);
                    }
                });
            },
            close: function(event, ui) {
                var me = this;
                ui.tooltip.hover(
                    function() {
                        $(this).stop(true).fadeTo(400, 1);
                    },
                    function() {
                        $(this).fadeOut("400", function() {
                            $(this).remove();
                        });
                    }
                );
                ui.tooltip.on("remove", function() {
                    $(me).tooltip("destroy");
                });
            },
        });
        $(this).tooltip("open");
    });
    $(document).on("click", ".tooltip-unflow-transaction", function() {

        $(this).tooltip({
            items: ".tooltip-unflow-transaction",
            open: function(event, ui) {
                var id = this.id;
                var agent_id = $(this).attr('data-id');

                $.ajax({
                    url: '/flight-tickets/ajax/agent-unflow-booking-transaction',
                    type: 'GET',
                    data: {
                        agent_id: agent_id,

                    },
                    success: function(response) {
                        // Setting content option
                        $("#" + id).tooltip('option', 'content', response);
                    }
                });
            },
            close: function(event, ui) {
                var me = this;
                ui.tooltip.hover(
                    function() {
                        $(this).stop(true).fadeTo(400, 1);
                    },
                    function() {
                        $(this).fadeOut("400", function() {
                            $(this).remove();
                        });
                    }
                );
                ui.tooltip.on("remove", function() {
                    $(me).tooltip("destroy");
                });
            },
        });
        $(this).tooltip("open");
    });

    $(document).on("click", ".tooltip-credit-limit-transaction", function() {

        $(this).tooltip({
            items: ".tooltip-credit-limit-transaction",
            open: function(event, ui) {
                var id = this.id;
                var agent_id = $(this).attr('data-id');

                $.ajax({
                    url: '/flight-tickets/ajax/agent-credit-limit-transaction',
                    type: 'GET',
                    data: {
                        agent_id: agent_id,

                    },
                    success: function(response) {
                        // Setting content option
                        $("#" + id).tooltip('option', 'content', response);
                    }
                });
            },
            close: function(event, ui) {
                var me = this;
                ui.tooltip.hover(
                    function() {
                        $(this).stop(true).fadeTo(400, 1);
                    },
                    function() {
                        $(this).fadeOut("400", function() {
                            $(this).remove();
                        });
                    }
                );
                ui.tooltip.on("remove", function() {
                    $(me).tooltip("destroy");
                });
            },
        });
        $(this).tooltip("open");
    });
    $(document).on("click", ".tooltip-remarks", function() {

        $(this).tooltip({
            items: ".tooltip-remarks",
            open: function(event, ui) {
                var id = this.id;
                var agent_id = $(this).attr('data-id');

                $.ajax({
                    url: '/flight-tickets/ajax/agent-remarks',
                    type: 'GET',
                    data: {
                        agent_id: agent_id,

                    },
                    success: function(response) {
                        // Setting content option
                        $("#" + id).tooltip('option', 'content', response);
                    }
                });
            },
            close: function(event, ui) {
                var me = this;
                ui.tooltip.hover(
                    function() {
                        $(this).stop(true).fadeTo(400, 1);
                    },
                    function() {
                        $(this).fadeOut("400", function() {
                            $(this).remove();
                        });
                    }
                );
                ui.tooltip.on("remove", function() {
                    $(me).tooltip("destroy");
                });
            },
        });
        $(this).tooltip("open");
    });
</script>

@endsection
