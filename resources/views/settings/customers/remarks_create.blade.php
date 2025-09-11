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
              
                </div>

            </div>


            <form class=" " action="{{ route('debitor-remarks.store') }}" method="POST">
                @csrf
                <div class="col-md-6">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Agent</label>
                        </div>
                        <div class="col-md-6">
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Remarks</label>
                        </div>
                        <div class="col-md-6">
                            <textarea name="remarks" id="" cols="30" rows="5" class="form-control" ></textarea>
                        </div>
                    </div>
                    
                
                </div>
                
               
             
              
               
               
             
                <div class="col-md-6">
                    <button class="btn btn-outline-behance btn-block btn-sm">Submit</button>
                </div>
            </form>
           
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
