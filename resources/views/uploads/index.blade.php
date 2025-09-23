@extends('app') {{-- if you’re using AdminLTE layout --}}

@section('title', 'Uploads')

@section('content_header')
    <h1>Uploaded Files</h1>
@stop

@section('content')
<div class="card">
<div class="card-header">
    <h3 class="card-title">Files List</h3>
    <div class="card-tools d-flex">
        <form action="{{ route('uploads.index') }}" method="GET" class="input-group input-group-sm mr-2">
            <input type="text" name="search" class="form-control float-right" placeholder="Search files..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>
    <div class="card-header">
        <h3 class="card-title">Files List</h3>
        <div class="card-tools">
            <a href="{{ route('uploads.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-upload"></i> Upload New
            </a>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>File Name</th>
                    <th>Type</th>
                    <th style="width:200px;">Description</th>
                    <th>Size</th>
                    <th>Uploaded By</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uploads as $upload)
                    <tr>
                        <td>{{ $upload->id }}</td>
                        <td>{{ $upload->file_name }}</td>
                        <td>{{ $upload->file_type ?? '-' }}</td>
                        <td style="font-size:12px;">{{ substr($upload->description, 0, 20) }}</td>
                        <td>{{ number_format($upload->file_size / 1024, 2) }} KB</td>
                        <td>{{ $upload->user->name ?? 'N/A' }}</td>
                        <td>{{ $upload->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            {{-- View --}}
                            <a href="{{ URL::temporarySignedRoute('uploads.view', now()->addMinutes(10), ['id' => $upload->id]) }}" 
                               target="_blank" 
                               onclick="window.open(this.href, 'newwindow', 'width=1000,height=1000'); return false;"
                               rel="noopener noreferrer"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>

                            {{-- Download --}}
                            <a href="{{ route('uploads.download', $upload->id) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Download
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('uploads.destroy', $upload->id) }}" 
                                  method="POST" 
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Are you sure you want to delete this file?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No files uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
