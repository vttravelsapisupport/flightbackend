@extends('layouts.app')
@section('css')

@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <hr>
                <pnr-reconciliation></pnr-reconciliation>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
