<x-admin-layout>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700;">
            <i class="ph ph-users" style="color: var(--accent);"></i> Manage Users
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
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Name</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Email</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Role</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600;">Joined</th>
                        <th style="padding: 12px; color: var(--text-secondary); font-weight: 600; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        @php $pendingInvite = $pendingEmails->contains($u->email); @endphp
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 12px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <img src="{{ $u->avatar_url }}" alt="" class="avatar avatar-sm">
                                    {{ $u->name }}
                                </div>
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $u->email }}</td>
                            <td style="padding: 12px;">
                                @if($pendingInvite)
                                    <span class="badge" style="background: rgba(234,179,8,0.12); color: #eab308; font-size: 11px;">⏳ Pending Invite</span>
                                @elseif($u->role === 'admin')
                                    <span class="badge" style="background: var(--danger-soft); color: var(--danger); font-size: 11px;">Admin</span>
                                @else
                                    <span class="badge" style="background: var(--bg-tertiary); color: var(--text-secondary); font-size: 11px;">Student</span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: var(--text-tertiary);">{{ $u->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px; text-align: right;">
                                @if($u->email !== 'tubamirza822@gmail.com')
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user completely?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="color: var(--danger); background: transparent; border: 1px solid var(--danger); padding: 4px 10px;">
                                        <i class="ph ph-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-16">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>

