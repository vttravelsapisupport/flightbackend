@extends('layouts.app')
@section('title','Markup Settings')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>

    .select2-container .select2-selection--single{
        height:35px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        line-height: 25px !important;
    }
    .amount{
        width:100px;
    }
    .toast.toast-error{
        opacity: 1 !important;
    }

    .toast-success{
        background-color: green !important;
    }
</style>

@endsection

@section('contents')

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Create a new Agent Markup
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <form action="" method="POST" id="newAgentMarkupForm">
            <div class="col-12 mb-3">
                <label for="">Agent Selection</label>
                <select
                name="agent_id"
                id="agent-select3"
                class="form-control form-control-sm  select2"
                style="width:430px"
                required
                >
                    @if($agent)
                    <option value="{{$agent->id}}" selected>{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                    @endif

                </select>
            </div>
            <div class="col-sm-12 mb-3">
                 <label for="">Enter the Markup Price</label>
                 <input type="number" class="form-control
                 form-control-sm
                  mb-1" name="markup_price"
                 min="0" max="99999" step='0' required>

                 <p class="small" style="color:red"> Note: Range between Rs 0 - 9999</p>
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary btn-block
                ">Submit</button>
            </div>
           </form>
        </div>

      </div>
    </div>
  </div>
  <div class="modal fade" id="GlobalMarkupSettingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Global Agent Markup
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <form action="" method="POST" id="newMarkupGlobalForm">
                @csrf
                <div class="col-sm-12 mb-3">

                    <label for="">Enter the Markup Price</label>
                    <input type="number" class="form-control
                    form-control-sm
                    mb-1" name="markup_price"
                    min="10" max="99999" step='10'
                    value="@if($global_markup_price){{ $global_markup_price->markup_price}}@endif"
                    required>

                    <p class="small" style="color:red"> Note: Range between Rs 0 - 9999</p>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary btn-block
                    ">Submit</button>
                </div>
           </form>
        </div>

      </div>
    </div>
  </div>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 grid-margin">
                    <h4 class="card-title text-uppercase">Markup Settings</h4>
                    <p class="card-description">Markup Settings in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">

                    @can('airline-markup create')

                    <button href="{{ route('airline-markup.create') }}"
                     type="button"
                     class="btn btn-sm btn-info mb-1 mr-2" data-toggle="modal" data-target="#exampleModal"
                     >New Agent Markup</button>
                    @endcan
                    @can('airline-markup create')

                    <button href="{{ route('airline-markup.create') }}"
                     type="button"
                     class="btn btn-sm btn-primary mb-1 mr-2" data-toggle="modal" data-target="#GlobalMarkupSettingModal"
                     >Global Markup</button>
                    @endcan

                </div>
            </div>
            <form class="forms-sample" method="GET" action="">
            @csrf
                <div class="form-group row">
                    <div class="col-sm-3">
                        <select  name="agent_id" id="agent-select2" class="form-control   form-control-sm select2">
                            @if($agent)
                            <option value="{{$agent->id}}" selected>{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                            @endif

                        </select>
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-outline-behance btn-block btn-sm">Search</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Markup Price</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(count($markups) != 0)
                            @foreach($markups as $key => $value)
                                <tr>
                                    <td>{{ 1 + $key }}</td>
                                    <td>{{ $value->agent->company_name }} {{ $value->agent->code }}</td>
                                    <td>Rs.{{ $value->markup_price }}</td>
                                    <td>@if($value->status == 1)
                                        <button
                                            class="badge badge-success isActiveButton"
                                            type="button"
                                            value="{{ $value->id }}"
                                        >Active</button>
                                        @elseif($value->status == 0)
                                        <button
                                        type="button"
                                            class="badge badge-danger isInactiveButton"
                                            value="{{ $value->  id }}"
                                            >Inactive</button>
                                        @endif
                                    </td>
                                    <td>{{ $value->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $value->updated_at->format('d-m-Y H:i:s') }}</td>

                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td class="text-center font-weight-bold"  colspan="7">No Result Found</td>
                                </tr>
                            @endif

                        </tbody>


                    </table>
                    <div class="mb-2 mt-2">
                        {{ $markups->links()}}

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        $(document).on('dblclick', '.amount', function () {
            $(this).removeAttr('readonly');
        });


        $(document).on('dblclick', '.isActiveButton', function(e) {
        let vm = $(this);
        let value = vm.val();

        $.ajax({
            url: '/settings/markups/' + value + '/online-status',
            type: "POST",
            data: {
                status: 0
            },
            success: function(resp) {
                if (resp.success) {
                    vm.removeClass('badge-success isActiveButton');
                    vm.addClass('badge-danger isInactiveButton');
                    vm.html('Inactive');
                    console.log(value);
                }
            }
        });
    });
        // MAKE IT INACTIVE
        $(document).on('dblclick', '.isInactiveButton', function(e) {
        let vm = $(this);
        let value = vm.val();

        $.ajax({
            url: '/settings/markups/' + value + '/online-status',
            type: "POST",
            data: {
                status: 1
            },
            success: function(resp) {
                if (resp.success) {
                    vm.removeClass('badge-danger isInactiveButton');
                    vm.addClass('badge-success isActiveButton');
                    vm.html('Active');
                    console.log(value);
                }
            }
        });
    });

        })
        $(document).on('focusout', '.amount', function () {
            $(this).attr('readonly', 'readonly');
        });

        $(document).on('change', '.amount', function () {
            if (!confirm('Are you sure want to update markup price?')) {
                return false;
            }
            var value = $(this).val();
            var id = $(this).data('id');
            $(this).attr('readonly', 'readonly');
            $.ajax({
                url: '{{ route("airline-markup.update") }}',
                type:"POST",
                data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                value: value
                },
                success:function(response){
                    if(response.success) {
                        toastr.success('Successfully updated markup price', { timeOut: 10000 });
                    }
                },
                error:function (response) {

                }
            });
        });

        $("#agent-select2").select2({
            placeholder: "Select a Agent",
            allowClear: true,
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

        $("#agent-select3").select2({
            placeholder: "Select a Agent",
            width: '100%',
            allowClear: true,
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


        $(".markup-action").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var type = $(this).data('type');

            if (!confirm('Are you sure want to '+ type +'?')) {
                return false;
            }

            $.ajax({
                url: '{{ route("airline-markup.update.status") }}',
                type:"POST",
                data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    value: type
                },
                success:function(response){
                    if(response.success) {
                        toastr.success('Successfully updated status', { timeOut: 10000 });
                        location.reload();
                    }
                },
                error:function (response) {

                }
            });
        });

    $('#newMarkupGlobalForm').submit(async(e)=>{
        e.preventDefault();
        let data = $("#newMarkupGlobalForm").serialize();
        $.ajax({
                url: '/ajax/markup-global-config',
                type:"POST",
                data:data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    if(response.success) {
                         toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 3000, // Set the timeout in milliseconds
                        };
                        toastr.success(response.message,'Success', {
                            onHidden: function() {
                             location.reload();
                         }});
                    }
                },
                error:function (response) {

                }
            });
    })
    $('#newAgentMarkupForm').submit(async(e)=>{
        e.preventDefault();
        let data = $("#newAgentMarkupForm").serialize();
        $.ajax({
                url: '/ajax/agent-markup',
                type:"POST",
                data:data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    if(response.success) {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 3000, // Set the timeout in milliseconds
                        };
                        toastr.success(response.message,'Success', {
                            onHidden: function() {
                             location.reload();
                         }});
                       // location.reload();
                    }
                },
                error:function (response) {

                }
            });

    })
</script>

@endsection
