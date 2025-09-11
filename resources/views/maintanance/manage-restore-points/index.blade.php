@extends('layouts.app')
@section('title','Manage Restore Points')
@section('css')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }

    .alert-container {
        position: fixed;
        bottom: 5px;
        left: 2%;
        width: 50%;
        margin: 0 25% 0 25%;
    }

    .alert {
        text-align: center;
        padding: 17px 0 20px 0;
        height: 54px;
        font-size: 20px;
        font-size: 0.875rem;
        color: white;
        background-color: #66a566;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Manage Restore Points</h4>
                    <p class="card-description">Restore activities to their previous state (To be used by the technical team, only after internal discussion).</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm datepicker" placeholder="Start Date" name="start_date" id="start_date" autocomplete="off" value="{{ request()->query('start_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control  form-control-sm  datepicker" placeholder="End Date" name="end_date" id="end_date" autocomplete="off" value="{{ request()->query('end_date') }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm">Search</button>
                    <button id="btn_restore" class="btn btn-primary btn-sm">Restore Selected</button>
                    <button id="btn_delete" class="btn btn-primary btn-sm">Delete Selected</button>
                </div>
            </form>
            <hr>
            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Comment</th>
                                <th>Created AT</th>
                                <th>Updated AT</th>
                                <th>Created By</th>
                                <th>Last Used By</th>
                                <th>Last Used At</th>
                                <th>Rollback Activities</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @if(!empty($data['RestorePoints']))
                            @foreach($data['RestorePoints'] as $key => $val)
                            <tr>
                                <td><input type="checkbox" id="rp_{{ $val->ID }}" name="selected_restore_points[]" value="{{ $val->ID }}" data-statement="{{ gzuncompress(base64_decode($val->RollbackActivities)) }}"></td>
                                <td>{{ $val->Type }}</td>
                                <td>{{ $val->Comment }}</td>
                                <td>{{ $val->CreatedAT }}</td>
                                <td>{{ $val->UpdatedAT }}</td>
                                <td>{{ $val->CreatedBy }}</td>
                                <td>{{ $val->LastUsedBy }}</td>
                                <td>{{ $val->LastUsedAT }}</td>
                                <td>
                                    <a href="#" class="btn-collapse" data-toggle="collapse" data-target="#collapse-{{ $val->ID }}" aria-expanded="false" aria-controls="collapse-{{ $val->ID }}">Show/Hide</a>
                                    <div class="collapse" id="collapse-{{ $val->ID }}">
                                    @php
                                        $rollbackActivities = gzuncompress(base64_decode($val->RollbackActivities));
                                        $lines = explode(';', $rollbackActivities);
                                        $lastLineIndex = count($lines) - 1;
                                    @endphp
                                    @foreach($lines as $index => $line)
                                        {{ $line }}
                                        @if($index !== $lastLineIndex)
                                            ;
                                        @endif
                                        <br>
                                    @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="text-align: left; padding: 10px 15px 10px;" colspan="31">Nothing to show</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alert-container" style="display:none;">
    <div class="alert"></div>
</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('.alert-container').hide();

        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        if (!start_date && !end_date) {
            let today = new Date();
            let date1 = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
            $('#end_date').val(date1);

            let today1 = new Date()
            let days = 86400000
            let sevenDaysAgo = new Date(today1 - (30 * days))
            let date2 = sevenDaysAgo.getFullYear() + '-' + (sevenDaysAgo.getMonth() + 1) + '-' + sevenDaysAgo.getDate();
            $('#start_date').val(date2);
        }

        $(".datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $('#start_date').change(function() {
            let start_date = $('#start_date').val();
            let start_date_year = start_date.split('-')[0];
            let start_date_month = start_date.split('-')[1];
            let start_date_day = start_date.split('-')[2];

            let endDate = $('#end_date');
            endDate.datepicker('destroy');
            endDate.datepicker({
                format: 'yyyy-mm-dd',
                startDate: new Date(start_date_year + '-' + start_date_month + '-' + start_date_day)
            });
            endDate.val(start_date);
            //endDate.attr("required", "true");
        })

        function get_selected_restore_point_ids() {
            /* Collect list of selected restore point ids */
            let selected_restore_point_ids = [];
            $('input[name="selected_restore_points[]"]:checked').each(function() {
                selected_restore_point_ids.push($(this).val());
            });
            let selectedItemsList = selected_restore_point_ids.join(",");
            return selectedItemsList;
        }

        $('#btn_restore').click((e) => {
            e.preventDefault();
            let promptResp = confirm("Are you sure you want to restore the selected items ?");

            if (promptResp) {
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let restore_points = get_selected_restore_point_ids();
                let restore_point_ids_array = restore_points.split(','); // Convert string to array
                let statement = "";

                $.each(restore_point_ids_array, function(index, id) {
                    statement += $("#rp_" + id).data('statement'); // Concatenate each ID's statement
                });

                $.ajax({
                    url: '/maintanance/manage-restore-operation',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        'ids': restore_points,
                        'statement': statement
                    },
                    success: function(success) {
                        if (success) {
                            msg("Selected Items Has Been Restored");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        $('#btn_delete').click((e) => {
            e.preventDefault();
            let promptResp = confirm("Are you sure you want to delete the selected restore points ?");

            if (promptResp) {
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let restore_points = get_selected_restore_point_ids();

                $.ajax({
                    url: '/maintanance/manage-delete-restore-points',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        'ids': restore_points
                    },
                    success: function(success) {
                        if (success) {
                            msg("Selected Items Has Been Deleted");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        function msg(msg) {
            var $alert = $('.alert');
            $alert.html(msg);
            var $alertContainer = $(".alert-container");
            $alertContainer.fadeIn().delay(2500).fadeOut();
            location.reload();
        }
    });
</script>
@endsection