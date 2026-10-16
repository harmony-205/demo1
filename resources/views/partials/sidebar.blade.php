@push('styles')
<style>
    .sidebar {
      background: var(--c-sidebar);
      color: #d9e4f0;
      padding: 20px 0;
    }

    .sidebar .user-info-card {
      padding: 0 20px 20px;
      margin-bottom: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar h3 {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #8fa9cc;
      padding: 0 20px;
      margin-bottom: 8px;
    }

    .sidebar ul {
      list-style: none;
      margin-bottom: 20px;
      padding-left: 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;
      color: #d9e4f0;
      text-decoration: none;
      font-size: 14px;
      border-left: 3px solid transparent;
      transition: background 0.2s, border-color 0.2s, color 0.2s;
    }

    .sidebar a i {
      font-size: 16px;
      opacity: 0.85;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.1);
      border-left-color: var(--c-accent);
      color: #fff;
    }

    .sidebar a.active i {
      color: var(--c-accent);
      opacity: 1;
    }
</style>
@endpush

<aside class="sidebar">
    @auth
        <div class="user-info-card d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-white text-truncate" style="font-size: 14px;">{{ auth()->user()->name }}</div>
                <div class="small">
                    @if (auth()->user()->isAdmin())
                        <span class="badge bg-danger" style="font-size: 10px;">👑 Quản trị viên</span>
                    @else
                        <span class="badge bg-success" style="font-size: 10px;">🎓 Giảng viên</span>
                    @endif
                </div>
            </div>
        </div>
    @endauth

    {{-- Menu động lấy từ CSDL, gom theo nhóm --}}
    @php
        $iconMap = [
            'Quản lý lớp học' => 'bi-journal-bookmark-fill',
            'Lớp học' => 'bi-journal-bookmark-fill',
            'Quản lý sinh viên' => 'bi-people-fill',
            'Sinh viên' => 'bi-people-fill',
            'Quản lý Menu' => 'bi-menu-button-wide-fill',
            'Quản lý menu' => 'bi-menu-button-wide-fill',
            'Quản lý User' => 'bi-person-badge-fill',
            'Quản lý user' => 'bi-person-badge-fill',
            'Thông tin cá nhân' => 'bi-person-circle',
            'Hồ sơ' => 'bi-person-circle',
            'Tổng quan' => 'bi-speedometer2',
        ];
    @endphp

    @foreach (collect($menuHienThi->get('sidebar', []))->groupBy('nhom') as $nhom => $menus)
        @if ($nhom !== '')
            <h3>{{ $nhom }}</h3>
        @endif
        <ul>
            @foreach ($menus as $menu)
                <li>
                    <a href="{{ $menu->url }}" @class(['active' => $menu->dangChon()])>
                        <i class="bi {{ $iconMap[$menu->ten] ?? 'bi-link-45deg' }}"></i>
                        <span>{{ $menu->ten }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</aside>
