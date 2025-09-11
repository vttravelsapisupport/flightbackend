@extends('layouts.app')

@section('contents')
<div class="container">
    <h2>Add API Key</h2>
    <form action="{{ route('api_keys.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Agent Code</label>
            <input type="text" name="agent_code" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
