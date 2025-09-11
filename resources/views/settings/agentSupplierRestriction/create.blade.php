@extends('layouts.app')
@section('title','Agent Supplier Restrictions')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>

@endsection
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">  {{ $agentDetails->company_name }} {{ $agentDetails->code }} Suppiler Restriction Create</h4>
            <p class="card-description">
                {{ $agentDetails->company_name }} {{ $agentDetails->code }} Suppiler Restriction Create
            </p>
            <form class="forms-sample" method="POST" action="{{ url('/settings/agent-supplier-restrictions') }}">
                @csrf
                <input type="hidden" name="agent_id" value="{{ $agent }}">
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Supplier</label>
                    <div class="col-sm-9">
                        <select name="supplier_id[]" id="supplier_id" class="form-control multiple" multiple>
                            @foreach($suppliers as $id => $supplier)
                            <option value="{{ $id }}"> {{ $supplier }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
        $('#supplier_id').select2();
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
                url: '/ajax/search/agents',
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
</script>
@endsection
