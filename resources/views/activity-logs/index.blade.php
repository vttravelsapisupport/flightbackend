@extends('layouts.app')
@section('title','Dashboard')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Activity Logs</h4>
                    </div>
                </div>

            </div>

            <div class="table-responsive-lg">
                <table id="sortable-table-2" class="table table-bordered table-sm text-left ">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $key => $val)
                        <tr>
                            <td>{{ 1 + $key }}</td>
                            <td>{{ $val->created_at->format('d-m-y h:i:s') }}</td>
                            <td>{{$val->log_name}}</td>
                            <td>{{$val->description}}</td>
                            
                        </tr>
                        @endforeach


                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

@endsection
@section('js')

@endsection
