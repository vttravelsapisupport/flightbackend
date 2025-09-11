@extends('layouts.app')
@section('title','Agent Ledger')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
          #rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

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
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Agents Ledger</h4>
                        <p class="card-description">
                            Agents Ledger
                        </p>
                    </div>

                </div>
                <form action="" class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <select required name="agent_id[]" id="agent-select2"
                                    class="form-control   form-control-sm select2" multiple>

                                <option value="">Select Agent</option>

                            </select>

                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ url('ledger-details') }}" method="POST">
                            @csrf

                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Agent</th>
                                    <th>Closing Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($agents as $key => $val)
                                    <tr>
                                        <td>{{ 1 + $key }}</td>
                                        <td>
                                            {{ $val->code }} {{ $val->company_name }} {{ $val->phone }}
                                            <input type="hidden" name="agents[]" value="{{ $val->id }}">
                                        </td>
                                        <th>
                                            <input type="text" class="form-control" name="amount[]"
                                                   value="@if ($val->getCurrentOpeningBalance){{ $val->getCurrentOpeningBalance->amount }} @endif">
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td>{{ $agents->links() }}</td>
                                </tr>
                                </tfoot>


                            </table>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(".datepicker").datepicker({todayHighlight: true, autoclose: true, format: 'dd-mm-yyyy'});
        $("#agent-select2").select2({
            allowClear: false,
            ajax: {
                url: '/ajax/search/agents',
                delay: 250,
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
