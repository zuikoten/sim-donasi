@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Role: {{ $role->name }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Role</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection