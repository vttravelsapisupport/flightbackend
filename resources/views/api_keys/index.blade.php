@extends('layouts.app')

@section('contents')
<div class="container">
    <h2>API Keys</h2>
    <a href="{{ route('api_keys.create') }}" class="btn btn-primary mb-3">Add New</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Agent Code</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($apiKeys as $i =>  $key)
            <tr>
                <td>{{ $i + 1}}</td>
                <td>{{ $key->agent_code }}</td>

                <td>{{ $key->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('api_keys.show', $key) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('api_keys.edit', $key) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('api_keys.destroy', $key) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this key?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $apiKeys->links() }}
</div>
@endsection
