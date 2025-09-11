@extends('layouts.app')
@section('title','Airports')
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
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Airports Alignment</h4>
                    <p class="card-description">Airports Alignment in the Appication.</p>
                </div>
                <div class="col-md-6 text-right">
                    @can('airport create')
                        <a href="{{ route('airports.create') }}" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">New Airport Alignment</a>
                    @endcan
                </div>
            </div>

            <form class="forms-sample row" method="GET" action="">

                <div class="col-md-3">
                    <select
                        name="airport_code"
                        id="airport-select3"
                        class="form-control form-control-sm  select2"
                        style="width:430px"
                    >
                    </select>
                </div>
                <div class="col-md-2 ">
                    <select name="status" id="status" class="form-control form-control-sm airline select2">
                        <option value="">Select Status</option>
                        <option value="1" @if (1== request()->query('status')) selected @endif>Active</option>
                        <option value="2" @if (2== request()->query('status')) selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-behance btn-block btn-sm"> Search</button>
                </div>


            </form>
            <hr>
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Airport Name</th>
                                <th>Airport Alias</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($airports as $k => $v)
                            <tr>
                                <td>{{  $k + 1 }}</td>
                                <td>{{  $v->airport_code }}</td>
                                <td>{{  $v->airport_align }}</td>
                                <td>
                                    @if($v->status == 0)
                                        <button for="" class="badge badge-danger isInactiveButton" value="{{$v->id}}">Inactive</button>
                                    @elseif($v->status == 1)
                                        <button for="" class="badge badge-success isActiveButton" value="{{$v->id}}">Active</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                    {{-- {{ $details->appends(request()->input())->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Create a new Airport Alignment
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <form action="/settings/airport-alignment" method="POST"
           >
                 @csrf
                <div class="col-12 mb-3">
                    <label for="">Airport Selection</label>
                    <select
                    name="airport_code"
                    id="airport-select"
                    class="form-control form-control-sm  select2"
                    style="width:430px"
                    required
                    >

                    </select>
                </div>
                <div class="col-sm-12 mb-3">
                    <label for="">Airport Alignment</label>
                    <select
                    name="airport_align"
                    id="airport-select2"
                    class="form-control form-control-sm  select2"
                    style="width:430px"
                    required
                    >
                    </select>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary btn-block">Submit</button>
                </div>
           </form>
        </div>

      </div>
</div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('dblclick', '.isActiveButton', function(e) {
        let vm = $(this);
        let value = vm.val();

        $.ajax({
            url: '/settings/airport-alignment/' + value + '/online-status',
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
            url: '/settings/airport-alignment/' + value + '/online-status',
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



    // $('.select2').select2();
        $("#airport-select, #airport-select2, #airport-select3").select2({
            placeholder: "Select a Airport",
            width: '100%',
            allowClear: true,
            ajax: {
                url: '/ajax/search/airports',
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
            minimumInputLength: 3,
        });


});
</script>
@endsection
