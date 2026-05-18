import './bootstrap';

// Theme Toggle
document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
    updateThemeIcon(theme);

    // Auto-dismiss flash messages
    document.querySelectorAll('.flash-message').forEach(el => {
        setTimeout(() => el.remove(), 4000);
    });

    // Initialize active tag styling
    document.querySelectorAll('input[name="tags[]"]:checked').forEach(cb => {
        const tag = cb.parentElement.querySelector('.tag');
        if (tag) tag.classList.add('active-tag');
    });
});

function updateThemeIcon(theme) {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;
    const icon = btn.querySelector('i');
    if (icon) {
        icon.className = theme === 'dark' ? 'ph ph-moon' : 'ph ph-sun';
    }
}

function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcon(next);
}
window.toggleTheme = toggleTheme;

// Mobile sidebar toggle
function toggleSidebar() {
    // On admin pages we attach `.admin-sidebar` (and it also includes `.app-sidebar`),
    // so choose ONE target to avoid double-toggling.
    const sidebar = document.querySelector('.admin-sidebar') || document.querySelector('.app-sidebar');
    sidebar?.classList.toggle('mobile-open');
}
window.toggleSidebar = toggleSidebar;

// Dropdown toggle
function toggleDropdown(id) {
    const menu = document.getElementById(id);
    if (menu) {
        const isOpen = menu.classList.contains('show');
        // Close all dropdowns first
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        if (!isOpen) menu.classList.add('show');
    }
}
window.toggleDropdown = toggleDropdown;

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
    }
});

// Close mobile sidebar on outside click
document.addEventListener('click', (e) => {
    const sidebar = document.querySelector('.admin-sidebar.mobile-open') || document.querySelector('.app-sidebar.mobile-open');
    if (sidebar && !e.target.closest('.app-sidebar') && !e.target.closest('.admin-sidebar') && !e.target.closest('#sidebar-toggle')) {
        sidebar.classList.remove('mobile-open');
    }
});

// AJAX Vote
async function vote(type, id, value) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) { window.location.href = '/login'; return; }
    try {
        const res = await fetch('/vote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ voteable_type: type, voteable_id: id, value })
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        const data = await res.json();
        if (data.score !== undefined) {
            // Update all matching vote containers (home + detail)
            document.querySelectorAll(`[data-voteable="${type}-${id}"]`).forEach(card => {
                const count = card.querySelector('.vote-count');
                if (count) count.textContent = data.score;
                card.querySelectorAll('.vote-btn').forEach(b => b.classList.remove('active-up', 'active-down'));
                if (data.user_vote === 1) card.querySelector('.vote-up')?.classList.add('active-up');
                if (data.user_vote === -1) card.querySelector('.vote-down')?.classList.add('active-down');
            });
        }
    } catch (e) { console.error('Vote failed', e); }
}
window.vote = vote;

// AJAX Bookmark
async function toggleBookmark(postId, btn) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) { window.location.href = '/login'; return; }
    try {
        const res = await fetch('/bookmark', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ post_id: postId })
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        const data = await res.json();
        if (btn) {
            btn.classList.toggle('text-accent', data.bookmarked);
            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = data.bookmarked ? 'ph-fill ph-bookmark-simple' : 'ph ph-bookmark-simple';
            }
        }
    } catch (e) { console.error('Bookmark failed', e); }
}
window.toggleBookmark = toggleBookmark;

// Smooth scroll to comment when linked
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash?.startsWith('#comment-')) {
        const el = document.querySelector(window.location.hash);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.style.background = 'var(--accent-soft)';
            setTimeout(() => el.style.background = '', 2000);
        }
    }
});

// Search shortcut: Ctrl+K or Cmd+K
document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('global-search')?.focus();
    }
});
