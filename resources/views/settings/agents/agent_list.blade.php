@extends('layouts.app')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
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
                    <h4 class="card-title text-uppercase">Agents</h4>
                    <p class="card-description">Agents in the application</p>
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
    
            <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm ">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agency Code</th>
                                <th>Agency Name</th>
                                <th>Balance</th>
                                <th>Contact Person</th>
                                <th>Selling Airport</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Mobile No</th>
                                <th>Email Id</th>
                                <th>Whatsapp No</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $key => $val)
                            <tr @if($val->type == 2) class="table-info" @endif>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ $val->code }}</td>
                                <td>{{ $val->company_name }}</td>
                                <td>{{ $val->opening_balance }}</td>
                                <td>{{ $val->contact_name }}</td>
                                <td>{{ $val->nearest_airport ? ($val->nearestAirportDetails) ? $val->nearestAirportDetails->cityCode : 'NA': "NA" }} </td>
                                <td>{{ ucwords($val->city) }} </td>
                                <td>{{ $val->state_id ? $val->state->name : "NA"   }} </td>
                                <td>{{ ucwords($val->phone) }} </td>
                                <td>{{ strtolower($val->email) }} </td>
                                <td>{{ ucwords($val->whatsapp) }} </td>
                                <td>{{ ucwords($val->address) }} </td>
                                <td>@if($val->status == 1)
                                    <div class="badge badge-success">Active</div>
                                    @elseif($val->status == 0)
                                    <div class="badge badge-danger">Inactive</div>
                                    @elseif($val->status == 2)
                                    <div class="badge badge-danger">Dormant</div>
                                    @elseif($val->status == 3)
                                    <div class="badge badge-danger">Duplicate</div>
                                    @elseif($val->status == 4)
                                    <div class="badge badge-danger">B2C</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-xs add_remarks" data-id="{{$val->id}}" data-toggle="modal" data-target="#exampleModal">Add</a>
                                </td>
                            </tr>
                         @endforeach
                        </tbody>
                    </table>
                    {{ $datas->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="exampleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Remarks</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" action="/settings/agents/update-remarks">
            @csrf
            <div class="modal-body">
                <textarea  rows="7" class="form-control" name="remarks" required></textarea>
                <input type="hidden" name="agent_id" id="agent_id"/>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection
@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true
    });

    $('.select2').select2();

    $(".add_remarks").click(function(){
        var agent = $(this).data('id');
        $("#agent_id").val(agent);
    });
</script>
@endsection
