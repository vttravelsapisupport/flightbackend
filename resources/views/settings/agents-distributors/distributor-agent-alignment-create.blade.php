@extends('layouts.app')
@section('title','Agents/Distributors')
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

                <div class="row form-group">
                    <div class="col-md-6">
                            <h5>Associate Agent</h5>
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('agents-distributors-alignment.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="distributor_id" value="{{ $distributor->id }}">
                        <div class="form-group row">
                            <label for="username" class="col-sm-3 col-form-label">Agent Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="username" placeholder="Enter the Username" name="username" value="{{ $distributor->company_name }}"  readonly required>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Agent </label>
                            <div class="col-sm-9">
                                <select name="agent_id" id="agent_id" class="form-control select2">
                                    <option value="">SelectAgent</option>
                                    @foreach($agents as $key => $value)
                                        <option value="{{ $value->id }}" @if($value->id == old('agent_id')) selected @endif>
                                            {{ $value->company_name }}   {{ $value->phone }} - {{ $value->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script>
        $('.select2').select2({});
        $('.btnDelete').click((e) => {
            let resp = confirm("Are you sure you want to delete the Agent ?");
            if (!resp) {
                e.preventDefault();
            }
        })
        // $('body').bind('copy paste', function(e) {
        //     e.preventDefault();
        //     return false;
        // });
    </script>
@endsection
