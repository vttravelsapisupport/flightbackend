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
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase"> Agent Notification</h4>
            <p class="card-description">
                Agent Notification
            </p>

            <form class="forms-sample row" method="POST" action="{{ route('agent-notification.update',$data->id) }}">
                @csrf
                @method('put')
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="company_name" class="col-sm-3 col-form-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" placeholder="Enter the title of the notification" name="title" value="{{ $data->title }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="body" class="col-sm-3 col-form-label">Body</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="body" placeholder="Enter the body of the notification" name="body" value="{{ $data->body }}" required>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="company_name" class="col-sm-3 col-form-label">Notification Type</label>
                        <div class="col-sm-9">
                            <select name="notification_type" id="notification_type" class="form-control">
                                <option value="">Select Notification Type</option>
                                <option value="1" @if($data->notification_type == 1) selected @endif >Landing Page Notification</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Notification Level</label>
                        <div class="col-sm-9">
                            <select name="notification_level" id="notification_level" class="form-control">
                                <option value="">Select Notification Type</option>
                                <option value="1" @if($data->notification_level == 1) selected @endif selected>Global</option>
                                <option value="2" disabled>Agent Level</option>
                                <option value="3" disabled>Sector Level</option>
                                <option value="4" disabled>Search Level</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">

                            <select name="status" id="status" class="form-control">

                                <option value="1" @if($data->status == 1) selected @endif>Active</option>
                                <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>


                </div>



                <button type="submit" class="btn btn-primary mr-2 btn-sm">UPDATE</button>

            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap"
        });


        $('#notification_level').on('change', function() {

            let type = this.value;
            $('.agentsDiv').hide();
            $('.sectorDiv').hide();
            if (type == 2) {
                $('.agentsDiv').show();
            } else if (type == 3) {
                $('.sectorDiv').show();

            }
        })
        $('#phone').change(function() {
            let phone = $('#phone').val();
            $('#username').val(phone);
        })
    });
</script>

@endsection