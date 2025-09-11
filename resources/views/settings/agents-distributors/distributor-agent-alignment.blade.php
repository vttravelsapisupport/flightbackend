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
                        <h5>Distributor Agent Alignment</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('agents-distributors-alignment.create',['distributor_id' => $distributor->id]) }}" class="btn btn-sm btn-primary"> Associate Agent</a>
                    </div>
                </div>
                <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm ">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Created Date and Time</th>
                                <th>Distributor Name</th>
                                <th>Distributor Code</th>
                                <th>Agency Name</th>
                                <th>Agency Code</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($agents->count() > 0)
                            @foreach($agents as $key => $value)
                                <tr>
                                    <td> {{ 1 + $loop->index }}</td>
                                    <td>
                                        {{ $value->created_at->format('d-m-Y h:i:s') }}
                                    </td>
                                    <td>
                                        {{ $value->distributor->company_name }}
                                    </td>
                                    <td>
                                        {{ $value->distributor->code }}
                                    </td>
                                    <td>
                                        {{ $value->agent->code }}
                                    </td>
                                    <td>
                                        {{ $value->agent->company_name }}
                                    </td>
                                    <td>
                                       <button class="btn btn-sm btn-danger deleteAlignmentButton" value="{{ $value->id }}">
                                           <i class="mdi mdi-trash-can"></i>
                                       </button>
                                    </td>

                                </tr>
                            @endforeach
                                @else
                                <tr>
                                    <th colspan="6" class="text-center font-weight-bold">No Result found</th>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>{{ $agents->appends( Request::except('page'))->links() }}</td>
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
    <script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('assets/js/tablesorter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script>
        $('.deleteAlignmentButton').click(function(e){
            $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let id  = $(this).val();
            let resp = confirm("Are you sure you want to delete the Alignment ?");
            if(resp){
                $.ajax({
                    url: '/settings/agents-distributors-alignment/'+id,
                    type: 'DELETE',
                    success:function(resp){

                        if(resp.success){
                            location.reload();
                        }
                    }


                })
            }else{
                e.preventDefault();
            }

        });
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
