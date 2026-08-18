@push('styles')
<style>
/* ---------- SIDEBAR ---------- */
    .sidebar {
      background-color: var(--mau-phu);
      color: #fff;
      padding: 20px 0;
      /* Cú pháp: flex: grow shrink basis
         0 0 240px = không giãn, không co, rộng đúng 240px */
      flex: 0 0 var(--rong-sidebar);
    }
    .sidebar h3 {
      font-size: 12px; text-transform: uppercase; letter-spacing: 1px;
      opacity: 0.7; padding: 0 20px; margin-bottom: 10px;
    }
    .menu-doc { list-style: none; margin-bottom: 24px; }
    .menu-doc a {
      display: block; color: #fff; text-decoration: none;
      padding: 10px 20px;
      border-left: 3px solid transparent;
      transition: all 0.2s;
    }
    .menu-doc a:hover {
      background-color: rgba(0,0,0,0.15);
      border-left-color: var(--mau-nhan);
      padding-left: 26px;
    }
    .menu-doc a.active {
      background-color: rgba(0,0,0,0.25);
      border-left-color: var(--mau-nhan);
      font-weight: 600;
    }
</style>
@endpush
  <aside class="sidebar">
    <h3>Nội dung môn học</h3>
    <ul class="menu-doc">
      <li><a href="#">Bài 1 — HTML cơ bản</a></li>
      <li><a href="#">Bài 2 — CSS Selector</a></li>
      <li><a href="#">Bài 3 — Box Model</a></li>
      <li><a href="#" class="active">Bài 4 — Flexbox</a></li>
      <li><a href="#">Bài 5 — CSS Grid</a></li>
      <li><a href="#">Bài 6 — Responsive</a></li>
    </ul>

    <h3>Tài nguyên</h3>
    <ul class="menu-doc">
      <li><a href="#">Slide bài giảng</a></li>
      <li><a href="#">Mã nguồn demo</a></li>
    </ul>
  </aside>