@extends('layouts.app')
@section('title','Settings')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 30px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  top: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #03aa6d;
}

input:focus + .slider {
  box-shadow: 0 0 1px #03aa6d;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">App Settings</h4>
                </div>
            </div>

            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Settings Name</th>
                                <th>Settings Code</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($settings as $key => $value)
                            <tr>
                                <td>{{ 1 +$key }}</td>
                                <td>{{ $value->settings_name }}</td>
                                <td>{{ $value->settings_code }}</td>
                                <td>
                                    @if($value->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @else
                                    <div class="badge badge-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <label class="switch">
                                            <input class="action_switcher" data-id="{{$value->id}}" type="checkbox" @if($value->status == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
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
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
#toast-container>.toast-success{
    background: #008140 !important;
}

#toast-container>.toast-warning{
    background: #f7b303 !important;
}
</style>
<script>
    $(document).ready(function() {
        $('.action_switcher').on('change', function() {
            var btn= $(this);
            var checked = $(this).is(':checked');
            var id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/settings/features/update',
                data: {
                    id: id,
                    status: checked
                },
                success: function(resp) {
                    if (resp.activated) {
                        toastr.success(resp.message);
                        btn.parent().parent().parent().parent().find('.badge').removeClass('badge-danger');
                        btn.parent().parent().parent().parent().find('.badge').addClass('badge-success');
                    }else{
                        toastr.warning(resp.message);
                        btn.parent().parent().parent().parent().find('.badge').removeClass('badge-success');
                        btn.parent().parent().parent().parent().find('.badge').addClass('badge-danger');
                    }
                }
            });
        });
    });
</script>
@endsection