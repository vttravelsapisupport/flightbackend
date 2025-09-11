@extends('layouts.app')
@section('title','Account Transactions')
@section('css')
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
                    <h4 class="card-title text-uppercase">Manage Account Transactions</h4>
                    <p class="card-description">Manage Account Transactions for the given agent code.</p>
                </div>
            </div>            
        </div>
    </div>
</div>

@endsection
