<x-admin-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700;">
            <i class="ph ph-user-minus" style="color: var(--accent);"></i> Account Deletion Requests
        </h1>
    </div>

    @if(session('status'))
        <div style="padding: 12px; background: var(--success-soft); color: var(--success); border-radius: 8px; margin-bottom: 24px;">
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">User</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Email</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Reason</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Requested At</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 12px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <img src="{{ $r->user->avatar_url }}" alt="" class="avatar avatar-sm">
                                    <span style="font-weight: 600;">{{ $r->user->name }}</span>
                                </div>
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $r->user->email }}</td>
                            <td style="padding: 12px; color: var(--text-secondary); max-width: 300px; word-break: break-word;">{{ $r->reason ?? 'No reason provided' }}</td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $r->created_at->format('M d, Y H:i') }} ({{ $r->created_at->diffForHumans() }})</td>
                            <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <form action="{{ route('admin.deletion-requests.approve', $r) }}" method="POST" onsubmit="return confirm('APPROVE DELETION: Are you sure you want to permanently delete this user account and all their data?');" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="color: white; background: var(--danger); padding: 4px 10px; border-radius: 6px; border: none; cursor: pointer;">
                                            <i class="ph ph-check"></i> Approve & Delete
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.deletion-requests.reject', $r) }}" method="POST" onsubmit="return confirm('Reject this deletion request?');" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="color: var(--text-secondary); background: var(--bg-tertiary); border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; cursor: pointer;">
                                            <i class="ph ph-x"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-tertiary); font-size: 14px;">
                                No pending deletion requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="mt-16">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
