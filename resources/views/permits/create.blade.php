@extends('app')

@section('title', 'Create Permit')

@section('content_header')
    <h1>Create Permit</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('permits.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Permit Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mt-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="form-group mt-3">
    <label for="permit_type">Permit Type</label>
    <select name="permit_type" id="permit_type" class="form-control" required>
        <option value="">-- Select Type --</option>
        <option value="Gatepass">Gatepass</option>
        <option value="Accreditation">Accreditation</option>
        <option value="Bring-In">Permit to Bring-In</option>
        <option value="Bring-Out">Permit to Bring-Out</option>
    </select>
    @error('permit_type') <small class="text-danger">{{ $message }}</small> @enderror
</div>


            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <a href="{{ route('permits.index') }}" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>
@stop
