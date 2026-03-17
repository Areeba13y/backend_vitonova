@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Contact Messages</h2>
        <span class="badge bg-warning text-dark fs-6">
            {{ $messages->where('is_read', false)->count() }} Unread
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr class="{{ $msg->is_read ? '' : 'fw-bold table-warning' }}">
                    <td>{{ $msg->id }}</td>
                    <td>{{ $msg->name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ Str::limit($msg->message, 80) }}</td>
                    <td>
                        @if($msg->is_read)
                            <span class="badge bg-success">Read</span>
                        @else
                            <span class="badge bg-warning text-dark">Unread</span>
                        @endif
                    </td>
                    <td>{{ $msg->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        @if(!$msg->is_read)
                        <form action="{{ route('admin.contacts.markRead', $msg) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-success">Mark Read</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this message?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $messages->links() }}

</div>
@endsection