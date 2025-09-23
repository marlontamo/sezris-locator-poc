@extends('app') {{-- or your AdminLTE layout --}}

@section('title', 'All Permits')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Permits</h4>
            <a href="{{ route('permits.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Permit
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Permit Type</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permits as $permit)
                        <tr>
                            <td>{{ $permit->id }}</td>
                            <td>{{ $permit->permit_type }}</td>
                            <td>{{ Str::limit($permit->description, 30) }}</td>
                            
                            <td>{{ \Carbon\Carbon::parse($permit->start_date)->format('M d, Y h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($permit->expiry_date)->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No permits found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $permits->links() }} {{-- pagination --}}
        </div>
    </div>
</div>
@endsection
