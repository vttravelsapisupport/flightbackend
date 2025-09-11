@extends('layouts.app')
@section('title','Airline Markup')
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

    .toast.toast-success{
        opacity: 1 !important;
    }
</style>

@endsection

@section('contents')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Airline Markup</h4>
                    <p class="card-description">Create a new Airline Markup in the Appication.</p>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('airline-markup.index') }}" class="btn btn-sm btn-primary mb-4 mr-2 float-right">Airline Markup</a>
                </div>
            </div>
            @can('airline-markup create')
            <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <form class="forms-sample" method="POST" action="{{ route('airline-markup.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Agents</label>
                                <div class="col-sm-9">
                                    <select name="agent" class="form-control select2">
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                        <option value="{{$agent->id}}">{{$agent->code}} {{$agent->company_name}} {{$agent->phone}} (BL: {{$agent->opening_balance}} CB: {{$agent->credit_balance}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @foreach($airlines as $key => $value)
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">{{$value}}</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="airlines[]"  value="{{$key}}">
                                    <input type="text" class="form-control" name="amounts[]" placeholder="Enter the Amount" name="amount">
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                        </form>


                    </div>
                </div>
            </div>
            </div>
            @endcan
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

        $('.select2').select2({});

        $(document).on('dblclick', '.amount', function () {
            $(this).removeAttr('readonly');
        });

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


        $(".markup-action").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var type = $(this).data('type');

            if (!confirm('Are you sure want to '+ type +'?')) {
                return false;
            }

            $.ajax({
                url: '/update-airline-status',
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
    });
</script>

@endsection
