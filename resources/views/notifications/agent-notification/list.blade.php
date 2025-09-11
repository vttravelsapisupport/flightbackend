@extends('layouts.app')
@section('title','Agent Notification')
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
                    <h4 class="card-title text-uppercase">Notification</h4>
                    <p class="card-description">Agents Notification </p>
                </div>
                <div class="col-md-6 text-right">

                    <a href="{{ route('agent-notification.create') }}" class="btn btn-sm btn-primary">New Agent Notification</a>

                </div>
            </div>

            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm ">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Notification Type</th>
                                <th>Notification Level</th>
                                <th>Status</th>
                                <th>Created At</th>

                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($agent_notifications->count() > 0)
                            @foreach($agent_notifications as $key => $val)
                            <tr>
                                <td>{{ 1+ $key }}</td>
                                <td>{{ $val->title }}</td>
                                <td>{{ $val->body }}</td>
                                <td> @if( $val->notification_type == 1)
                                    Landing Page Notification
                                    @else
                                    @endif
                                </td>
                                <td>@if( $val->notification_level == 1)
                                    Global
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if( $val->status == 1)
                                    <label for="" class="badge badge-success">Active</label>
                                    @else
                                    <label for="" class="badge badge-danger">Inactive</label>
                                    @endif
                                </td>
                                <td>{{ $val->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <div>
                                        <a href="{{ route('agent-notification.show',$val->id) }}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{ route('agent-notification.edit',$val->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <th colspan="12" class="text-center">No Result Found</th>
                            </tr>
                            @endif



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