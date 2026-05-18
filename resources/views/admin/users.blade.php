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
                                <button type="button" class="btn btn-sm" style="color: var(--info); background: transparent; border: 1px solid var(--info); padding: 4px 10px; cursor: pointer;" onclick="toggleBadgeForm('badge-form-{{ $u->id }}')">
                                    <i class="ph ph-badge"></i> Badges
                                </button>
                                @if($u->email !== 'tubamirza822@gmail.com')
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user completely?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="color: var(--danger); background: transparent; border: 1px solid var(--danger); padding: 4px 10px;">
                                        <i class="ph ph-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        <tr id="badge-form-{{ $u->id }}" style="display: none; border-bottom: 1px solid var(--border-light);">
                            <td colspan="5" style="padding: 24px;">
                                <div style="background: var(--bg-secondary); padding: 16px; border-radius: 8px;">
                                    <h4 style="margin-bottom: 12px; font-weight: 600;">Current Badges for {{ $u->name }}</h4>
                                    
                                    {{-- Display existing badges --}}
                                    <div style="margin-bottom: 16px;">
                                        @forelse($u->customBadges as $customBadge)
                                            <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--bg-tertiary); padding: 8px 12px; border-radius: 6px; margin-right: 8px; margin-bottom: 8px; color: {{ $customBadge->color }};">
                                                {{ $customBadge->icon }} {{ $customBadge->label }}
                                                <form action="{{ route('admin.users.removeBadge', [$u, $customBadge]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" style="background: transparent; border: none; padding: 0; color: var(--text-secondary); cursor: pointer; font-size: 14px; margin-left: 4px;">×</button>
                                                </form>
                                            </div>
                                        @empty
                                            <p style="color: var(--text-secondary); font-size: 13px;">No custom badges yet. Reputation badge: {{ $u->reputation_badge['label'] }}</p>
                                        @endforelse
                                    </div>

                                    {{-- Add badge form --}}
                                    <form action="{{ route('admin.users.addBadge', $u) }}" method="POST" style="display: grid; gap: 8px;">
                                        @csrf
                                        <div>
                                            <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">Badge Label</label>
                                            <input type="text" name="label" placeholder="e.g., Expert, Top Contributor" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);">
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">Icon (emoji or symbol)</label>
                                            <input type="text" name="icon" maxlength="10" placeholder="e.g., 🏆 or ★" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);">
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">Color (hex code)</label>
                                            <input type="text" name="color" placeholder="#7c5cfc" pattern="^#[0-9a-f]{6}$" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);">
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 8px; font-size: 13px;">
                                            <i class="ph ph-plus"></i> Add Badge
                                        </button>
                                    </form>
                                </div>
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

<script>
function toggleBadgeForm(formId) {
    const form = document.getElementById(formId);
    form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
}
</script>
