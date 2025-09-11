@extends('layouts.app')
@section('title','Credits/Debits')
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
            <h4 class="card-title text-uppercase">New Credit/Debit </h4>
            <p class="card-description">
                Entry Credit / Debit
            </p>

            <form class="forms-sample row" method="POST" action="{{ route('credits-debits.store') }}">
                @csrf
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="alias" class="col-sm-3 col-form-label">Agent</label>
                        <div class="col-sm-9">
                            <select name="agent_id" id="agent-select2" class="form-control select2">
                                <option value="">Select Agency</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_name" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9">
                            <select name="type" id="type" class="form-control">
                                <option value="1"  @if(old('type') == '1') selected @endif>Temporary Credit</option>
                                <option value="5"  @if(old('type') == '5') selected @endif>Temporary Debit</option>
{{--                                <option value="7">Distributor Balance</option>--}}

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact_name" class="col-sm-3 col-form-label">Amount</label>
                        <div class="col-sm-9">
                            <input  type="number"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    class="form-control"
                                    id="amount" placeholder="Enter the Amount" name="amount" value="{{ old('amount') }}" required maxlength="7">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact_name" class="col-sm-3 col-form-label">Expire After </label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="expire_day" placeholder="Enter the Expire Day" name="expire_day" value="{{ old('expire_day') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact_name" class="col-sm-3 col-form-label">Remarks</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="remarks" placeholder="Enter the Remarks" name="remarks" value="{{ old('remarks') }}" remark>
                        </div>
                    </div>

                </div>



                <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('keydown', '.select2', function(e) {
        if (e.originalEvent && e.which == 40) {
            e.preventDefault();
            $(this).siblings('select').select2('open');
        }
    });

    $('select').select2({
        selectOnClose: true
    });

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
                url: '/flight-tickets/ajax/search/agents?defaulter=true',
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
