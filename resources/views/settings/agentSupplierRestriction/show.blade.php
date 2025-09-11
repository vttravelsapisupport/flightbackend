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
                        <h4 class="card-title text-uppercase">{{ $agentDetails->company_name }} ( {{ $agentDetails->code }})  Supplier Restriction</h4>

                    </div>
                    <div class="col-md-6 text-right">
                        @can('agent_supplier_restriction create')
                            <a href="{{ url('settings/agent-supplier-restrictions/create?agent_id='.$id) }}" class="btn btn-sm btn-primary">New Supplier Restriction</a>
                        @endcan
                    </div>

                </div>


                <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $i => $d)
                                <tr>
                                    <td>
                                        {{ 1 +$i }}
                                    </td>
                                    <td>
                                        {{ $d->agent->company_name }}  {{ $d->agent->code }}
                                    </td>
                                    <td>
                                        {{ $d->supplier->name }}
                                    </td>
                                    <td>
                                        @if($d->status == 1)
                                            <div class="badge badge-success">Active</div>
                                        @else
                                            <div class="badge badge-danger">Inactive</div>
                                        @endif

                                    </td>
                                    <td>
                                        {{ $d->user->first_name }}
                                    </td>
                                    <td>
                                        {{ $d->created_at->format('d-m-Y h:i:s') }}
                                    </td>
                                    <td>

                                        @can('agent_supplier_restriction delete')
                                            <form action="{{ route('agent-supplier-restrictions.destroy', $d->id) }}" method="post">
                                                <input class="btn btn-outline-danger btn-sm" type="submit" value="Delete" />
                                                <input type="hidden" name="agent_id" value="{{$agentDetails->id}}"/>
                                                {!! method_field('delete') !!}
                                                {!! csrf_field() !!}
                                            </form>

                                        @endcan
                                    </td>

                                </tr>
                            @endforeach



                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="7">{{ $data->links() }}</td>
                            </tr>
                            </tfoot>
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
