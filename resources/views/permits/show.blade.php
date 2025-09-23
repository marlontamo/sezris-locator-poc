@extends('app') {{-- or your AdminLTE layout --}}

@section('title', 'Permit Details')

@section('content')
<div class="container mt-4">
    {{-- Permit Card --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4>Permit Details</h4>
        </div>
        <div class="card-body">
            <p><strong>Permit Type:</strong> {{ $permit->permit_type }}</p>
            <p><strong>Description:</strong> {{ $permit->description }}</p>
            {{-- <p><strong>Applying entity:</strong> {{ $permit->user->name }}</p> --}}
            <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($permit->start_date)->toFormattedDateString() }}</p>
            <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($permit->expiry_date)->toFormattedDateString() }}</p>
            <p>
            <strong>Status:</strong>
@php
    $allApproved = $permit->approvals->whereIn('step', [1,2,3])->every(fn($a) => $a->status === 'approved');
@endphp

<span class="badge bg-{{ $allApproved ? 'success' : ($permit->approvals->contains('status', 'rejected') ? 'danger' : 'warning') }}">
    {{ $allApproved ? 'Approved' : ($permit->approvals->contains('status', 'rejected') ? 'Rejected' : 'Pending') }}
</span>
            </p>
            {{-- Requirements Section --}}
<div class="card mt-4">
    <div class="card-header">
        <h5>Uploaded Requirements</h5>
    </div>
    <div class="card-body p-0">
        @if ($permit->uploads->count() > 0)
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Uploaded At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permit->uploads as $upload)
                        <tr>
                            <td>{{ $upload->file_name }}</td>
                            <td>{{ strtoupper($upload->file_type) }}</td>
                         <td>{{ number_format($upload->file_size / 1024, 2) }} KB</td>
                          <td>{{ $upload->created_at->format('M d, Y h:i A') }}</td>
                            <!-- <td>{{ $upload->user?->name ?? 'N/A' }}</td> -->
                            <td>
                                <a href="{{ $upload->file_url }}" target="_blank" class="btn btn-sm btn-primary">
                                    View
                                </a>
                                <a href="{{ $upload->file_url }}" download class="btn btn-sm btn-success">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="p-3 mb-0"><em>No requirements uploaded yet.</em></p>
        @endif
    </div>
</div>
        </div>
    </div>

    {{-- Approvals Table --}}
    <div class="card">
        <div class="card-header">
            <h5>Approval Steps</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Step</th>
                        <th>Approver</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Acted At</th> {{-- ✅ New column --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permit->approvals as $approval)
                        <tr>
                            <td>{{ $approval->step }}</td>
                            <td>{{ $approval->approver->name }}</td>
                            <td>
                                <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : ($approval->status === 'rejected' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($approval->status) }}
                                </span>
                            </td>
                            <td>{{ $approval->remarks ?? '-' }}</td>
                            <td>
                                @if ($approval->acted_at)
                                    {{ \Carbon\Carbon::parse($approval->acted_at)->format('M d, Y h:i A') }}
                                @else
                                    <em>-</em>
                                @endif
                            </td>
                            <td>
    @php
        // Check if previous step is approved (or this is step 1)
        $previousApproved = $approval->step == 1 
            || $permit->approvals->where('step', $approval->step - 1)->first()?->status === 'approved';
    @endphp

    {{-- If current user is the approver, status pending, and previous step approved --}}
    @if ($approval->approver_id === auth()->id() && $approval->status === 'pending'|| $approval->status === 'returned' && $previousApproved)
        <form action="{{ route('approvals.approve', $approval->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Approve</button>
        </form>

        <form action="{{ route('approvals.reject', $approval->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
        </form>
    @elseif ($approval->status === 'rejected' && $approval->step > 1)
        {{-- Return button appears only if rejected and not step 1 --}}
        <form action="{{ route('approvals.return', $approval->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">Return to Previous Step</button>
        </form>
    @else
        <em> - </em>
    @endif
</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
