@extends('layouts.app')

@section('contents')
<div class="container">
    <h2>Edit API Key</h2>

    <form action="{{ route('api_keys.update', $apiKey->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Agent Code</label>
            <input type="text" name="agent_code" class="form-control" value="{{ old('agent_code', $apiKey->agent_code) }}" required>
        </div>

        <!-- <div class="mb-3">
            <label>Product ID</label>
            <input type="text" name="product_id" class="form-control" value="{{ old('product_id', $apiKey->product_id) }}" required>
        </div>

        <div class="mb-3">
            <label>Subscription ID</label>
            <input type="text" name="subscription_id" class="form-control" value="{{ old('subscription_id', $apiKey->subscription_id) }}" required>
        </div> -->

        <div class="mb-3">
            <label>Primary API Key</label>
            <input type="text" name="api_key_primary" class="form-control" value="{{ old('api_key_primary', $apiKey->api_key_primary) }}">
        </div>

        <div class="mb-3">
            <label>Secondary API Key</label>
            <input type="text" name="api_key_secondary" class="form-control" value="{{ old('api_key_secondary', $apiKey->api_key_secondary) }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" {{ $apiKey->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$apiKey->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('api_keys.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
