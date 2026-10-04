  @push('styles')
  <style>
  /* ---------- HEADER ---------- */
    .header {
      background-color: var(--mau-chinh);
      color: #fff;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      flex-shrink: 0;
    }
    .logo { font-size: 20px; font-weight: 700; text-decoration: none; color: #fff; }
    .logo span { color: var(--mau-nhan); }

    .menu-ngang { list-style: none; display: flex; gap: 6px; margin-bottom: 0; padding-left: 0; }
    .menu-ngang a {
      color: #fff; text-decoration: none;
      padding: 8px 14px; border-radius: 4px;
      transition: background-color 0.2s;
    }
    .menu-ngang a:hover,
    .menu-ngang a.active { background-color: rgba(255,255,255,0.18); font-weight: 600; }
</style>
  @endpush
  <header class="header">
    <a href="{{ route('lophocs.index') }}" class="logo">HUCE<span>.Web</span></a>
    <nav>
      <ul class="menu-ngang">
        <li><a href="{{ route('lophocs.index') }}" class="{{ request()->routeIs('lophocs.*') ? 'active' : '' }}">Quản lý Lớp học</a></li>
        <li><a href="{{ route('menus.index') }}" class="{{ request()->routeIs('menus.*') ? 'active' : '' }}">Quản lý Menu</a></li>
      </ul>
    </nav>
  </header>