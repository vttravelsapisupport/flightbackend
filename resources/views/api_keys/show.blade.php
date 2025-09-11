@extends('layouts.app')

@section('contents')
<div class="container">
    <h2>API Key Details</h2>

    <table class="table table-bordered">
        <tr><th>Agent ID</th><td>{{ $apiKey->agent_id }}</td></tr>
        <tr><th>Agent Code</th><td>{{ $apiKey->agent_code }}</td></tr>
       
        <tr><th>Live API Key</th><td>{{ $apiKey->api_key_primary }}</td></tr>
        <tr><th>Test API Key</th><td>{{ $apiKey->api_key_secondary }}</td></tr>
        <tr><th>Status</th><td>{{ $apiKey->status ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created At</th><td>{{ $apiKey->created_at }}</td></tr>
        <tr><th>Updated At</th><td>{{ $apiKey->updated_at }}</td></tr>
    </table>

    <a href="{{ route('api_keys.edit', $apiKey->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('api_keys.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
