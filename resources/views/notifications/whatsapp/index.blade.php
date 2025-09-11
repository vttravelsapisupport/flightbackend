@extends('layouts.app')
@section('title','WhatsApp')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
    rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.4.3/dist/multiple-select.min.css">
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
    .ms-choice {
      border: none !important;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">WhatsApp</h4>
                    <p class="card-description">WhatsApp Name List Tickets in the Application.</p>
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
            <div class=" mb-3">
                <form action="" class="row">
                    <div class="col-md-2">
                        <label for="">Select Ex</label>
                        <select name="ex" class="form-control" id="ex" required>
                            <option value="">Select Ex</option>
                            @foreach($ex as $key =>$val)
                            <option value="{{$val->id}}" @if($val->id == request()->query('ex')) selected @endif>{{
                                $val->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="">Days</label>
                        <select name="days" id="days" class="form-control">
                            <option value="3" @if(3==request()->query('days')) selected @endif>3</option>
                        </select>
                    </div>
                    <div  class="col-md-2">
                        <label for="#destination_id">Sectors</label>
                        <select name="destination_id[]" id="destination_id" class="form-control" multiple required></select>
                        <!-- <input name="destination_id_order" id="destination_id_order" type="hidden"> -->
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success mt-4" name="search" value="true">Search</button>
                    </div>
                </form>
            </div>
            @if($output_datas)
                @php $whatsapp_data_count = count($output_datas); @endphp
                <div class="p-5">
                    @include('../../emails.notifications.whatsapp')
                </div>
                @if (false)
                @if ($whatsapp_data_count >= 1 && $whatsapp_data_count <= 7)
                    <div class="mb-5">
                        <form action="{{ route('whatsapp.send') }}" method="post">
                            @csrf
                            <div class="row">
                                <div  class="col-md-6">
                                    <label for="#airport_id">Agent with Airports</label>
                                    <select name="airport_id[]" id="airport_id" class="form-control" multiple required></select>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="whatsapp_ex" value="{{ request()->query('ex') }}">
                                    <input type="hidden" name="whatsapp_days" value="{{ request()->query('days') }}">
                                    <input type="hidden" name="whatsapp_destination_ids" value="{{ json_encode(request()->query('destination_id')) }}">
                                    <input type="hidden" name="whatsapp_data" value="{{ json_encode($whatsapp_data) }}">
                                    <input type="hidden" name="whatsapp_data_count" value="{{ $whatsapp_data_count }}">
                                    <button type="submit" class="btn btn-primary btn-block">Queue Messages</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        Cannot send more than 7 sectors.
                    </div>
                @endif
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/wenzhixin/multiple-select@1.4.3/dist/multiple-select.min.js"></script>
<script>
    $(".datepicker").datepicker({ todayHighlight: true, autoclose: true, format: 'dd-mm-yyyy' });
    $('.select2').select2({

    });
</script>
<script>
    $(document).ready(function() {
        function loadSectors() {
            var selected = $('#ex').children("option:selected").val();
            $.ajax({
                url: '/destination-based-on-ex/' + (selected ? selected : 0),
                type: 'GET',
                success: function(r) {
                    var options = '';
                    r.forEach(e => {
                        options += '<option value="' + e.id + '">' + e.name + '</option>'
                    })
                    $('#destination_id').empty();
                    $('#destination_id').append(options);
                    $('#destination_id').multipleSelect("refresh");
    
                    // Check if there are URL parameters and restore selected values
                    var urlParams = new URLSearchParams(window.location.search);
                    var selectedValues = urlParams.getAll("destination_id[]");
                    if (selectedValues.length > 0) {
                        $('#destination_id').multipleSelect("setSelects", selectedValues);
                        $('#destination_id').multipleSelect("refresh");
                    }
                }
            })
        }
    
        $('#destination_id').multipleSelect({
            placeholder: 'Select options',
            selectAll: true,
            filter: true
        });
    
        // TODO: submit in ordrer
        // $('#destination_id').change(function() {
        //     // var array = [];
        //     // var selectedValues = $('#destination_id').multipleSelect("getSelects", "array");
        //     // for ()
        //     // var numSelected = $('#destination_id').multipleSelect("getSelects").length;
        //     // var placeholder = numSelected + ' item(s) selected';
        //     // // TODO: show count
        //     // $('#destination_id').multipleSelect("setPlaceholder", placeholder);
        // });
    
        $('#ex').change(function() {
            loadSectors();
        });
    
        loadSectors();



        // airports chooser

        function loadAirports() {
            var selected = $('#ex').children("option:selected").val();
            $.ajax({
                url: '/airport-based-on-ex/' + (selected ? selected : 0),
                type: 'GET',
                success: function(res) {
                    console.log(res)
                    var options = '';
                    res.forEach(e => {
                        options += '<option value="' + e.id + '">' + e.code + ' - ' + e.name + '</option>'
                    })
                    $('#airport_id').empty();
                    $('#airport_id').append(options);
                    $('#airport_id').multipleSelect("refresh");
    
                    // Check if there are URL parameters and restore selected values
                    var urlParams = new URLSearchParams(window.location.search);
                    var selectedValues = urlParams.getAll("airport_id[]");
                    if (selectedValues.length > 0) {
                        $('#airport_id').multipleSelect("setSelects", selectedValues);
                        $('#airport_id').multipleSelect("refresh");
                    }
                }
            })
        }
    
        $('#airport_id').multipleSelect({
            placeholder: 'Select options',
            selectAll: true,
            filter: true
        });
    
        $('#ex').change(function() {
            loadAirports();
        });
    
        loadAirports();
    });
</script>
@endsection