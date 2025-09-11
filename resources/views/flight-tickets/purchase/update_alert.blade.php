@extends('layouts.app')
@section('title','Purchase')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 17px !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div
    class="modal fade"
    id="IntimationModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="IntimationModal"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="IntimationModal1">

                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <namelist-initimation></namelist-initimation>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
                <div class="alert alert-info" role="alert">
                    Please
                    <a
                    href="javascript:void()"
                    data-toggle="modal"
                    data-target="#IntimationModal"
                    target="__blank"
                    class="btn-link"
                    >
                    click here
                    </a>  send the intimation for the below changes.
                    <ul>
                        @foreach($modifed_key as $key => $v)
                        <li >{!! $v !!}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="table-responsive">


                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark ">
                        <tr>
                            <th>#</th>
                            <th>Agent</th>
                            <th>Bill No</th>
                            <th>Destination</th>
                            <th>PNR No.</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $v)
                            @php
                                $purchase_entry_id =$v->purchase_entry_id;
                            @endphp
                            <tr>
                                <td>{{ 1 +$k }}</td>
                                <td>{{ $v->agent->code }}</td>
                                <td>{{ $v->bill_no }}</td>
                                <td>{{ $v->destination }}</td>
                                <td>{{ $v->pnr }}</td>
                                <td>{{ $v->created_at->format('d-m-Y h:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-3">

                        <form action="/flight-tickets/purchase/{{$purchase_entry_id}}/update-acknowledge" method="POST">

                            @csrf
                            <input type="checkbox" name="acknowledge" > &nbsp; Please acknowledge that you have send the initimation to the agents
                            <br>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

