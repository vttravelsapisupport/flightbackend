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
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Agent Supplier Restrictions</h4>
                    <p class="card-description">Agent Supplier Restrictions in the Appication.</p>
                </div>
            </div>
            <form action="">
            <div class="row mb-2">

                    <div class="col-md-4">
                    <select name="agent_id" id="agent-select2" class="form-control select2">
                        @if($agent)
                            <option value="{{$agent->id}}">{{$agent->code .' '. $agent->company_name . ' ' . $agent->phone . ' BL '. $agent->opening_balance. ' CB ' . $agent->credit_balance}}</option>
                        @endif
                        <option value="">Select Agent</option>
                    </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary"> Search</button>
                    </div>


            </div>
            </form>

            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Code</th>
                                <th>Agent</th>
                                <th>Mobile</th>
                                <th>Supplier Blocked Count</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $d)
                            <tr>
                                <td>
                                    {{ 1 +$i }}
                                </td>
                                <td>{{ $d->code }}  </td>
                                <td>
                                    {{ $d->company_name }}
                                </td>
                                <td>{{ $d->phone }}</td>
                                <td>
                                    {{ $d->getSupplierRestrictionCount->count() }}
                                </td>

                                <td>
                                    <a href="{{ route('agent-supplier-restrictions.show',$d->id) }}" class="btn btn-sm btn-primary">View</a>

                                </td>

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
    </script>
@endsection
