@push('styles')
<style>
    .header {
      background: var(--c-header);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      position: sticky;
      top: 0;
      z-index: 100;
      height: var(--header-height);
    }

    .header .logo {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-decoration: none;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .header .logo span {
      color: var(--c-accent);
    }

    .header nav ul {
      display: flex;
      gap: 18px;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .header nav a {
      color: #d9e4f0;
      text-decoration: none;
      font-size: 14px;
      padding: 6px 4px;
      border-bottom: 2px solid transparent;
      transition: color 0.2s, border-color 0.2s;
    }

    .header nav a:hover,
    .header nav a:focus {
      color: #fff;
      border-bottom-color: var(--c-accent);
    }

    .user-pill {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--c-accent);
      color: #1e3a5f;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 14px;
    }
</style>
@endpush
<header class="header">
    <div class="d-flex align-items-center gap-4">
        <a href="{{ route('lophoc.index') }}" class="logo">
            <i class="bi bi-mortarboard-fill text-warning"></i>
            HUCE<span>.Classroom</span>
        </a>
        <nav class="d-none d-md-block">
            <ul>
                @foreach ($menuHienThi->get('header', []) as $menu)
                    <li><a href="{{ $menu->url }}">{{ $menu->ten }}</a></li>
                @endforeach
            </ul>
        </nav>
    </div>

    <div class="user-pill">
        @auth
            <div class="d-none d-sm-flex flex-column text-end">
                <span class="fw-semibold text-white small leading-tight">{{ auth()->user()->name }}</span>
                <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-success' }}" style="font-size: 10px; align-self: flex-end;">
                    {{ auth()->user()->isAdmin() ? '👑 Admin' : '🎓 Giảng viên' }}
                </span>
            </div>
            <a href="{{ route('profile.index') }}" class="user-avatar text-decoration-none shadow-sm" title="Trang cá nhân: {{ auth()->user()->name }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-1">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1" title="Đăng xuất khỏi hệ thống">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-md-inline">Đăng xuất</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-warning fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
            </a>
        @endauth
    </div>
</header>
