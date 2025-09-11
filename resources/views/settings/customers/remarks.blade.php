@extends('layouts.app')
@section('title','Agent Remarks')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Agent Remarks</h4>
                    <p class="card-description">Agent Remarks in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="mb-3">
                      
                        <button class="btn btn-success" id="excelDownload" type="button">
                            <i class="mdi mdi-file-excel"></i>Export Excel
                        </button>
                    </div>
                </div>

            </div>


            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <select name="agent_id" id="agent-select2" class="form-control form-control-sm select2">
                        <option value="">Select Agent</option>
                        @if ($agents)
                            <option value="{{ $agents->id }}" @if ($agents->id == request()->query('agent_id')) selected @endif>{{ $agents->code }}
                                {{ $agents->company_name }} {{ $agents->phone }} BL={{ $agents->opening_balance }}
                                CR={{ $agents->credit_balance }}
                            </option>
                         @endif
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
                <div class="col-md-2">
                    <button class="btn btn-outline-behance btn-block btn-sm">Search</button>
                </div>
            </form>
            <div class="row mt-3">

                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered  table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Remark</th>
                                <th>Date and time</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $val)
                            <tr>
                                <th>{{ 1 + $key }} </th>
                                <td>{{ $val->user->first_name }} {{ $val->user->last_name }} </td>
                                <td>{{ $val->remarks }}</td>
                                <td>{{ $val->created_at->format('d-m-Y h:i:s') }}</td>
                                <td></td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                           
                        </tfoot>
                    </table>
                    <div class="mt-2">
                 
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
<script>
    let filename = "{{ Carbon\Carbon::now()->format('d-m-y hh:mm:ss')}}" + "-sales.csv";
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
    $(document).ready(function() {

        $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
        $("#from").change(function() {
		let from = $("#from").val();

			$("#to").val(from);


	    });

        $('.select2').select2({

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
@endsection
