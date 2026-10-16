@php($sinhvien = $sinhvien ?? null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="ma_sv" class="form-label fw-semibold">Mã sinh viên <span class="text-danger">*</span></label>
        <input type="text" id="ma_sv" name="ma_sv"
               class="form-control @error('ma_sv') is-invalid @enderror"
               value="{{ old('ma_sv', $sinhvien->ma_sv ?? '') }}"
               placeholder="Ví dụ: SV680001" required>
        @error('ma_sv')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="ten" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
        <input type="text" id="ten" name="ten"
               class="form-control @error('ten') is-invalid @enderror"
               value="{{ old('ten', $sinhvien->ten ?? '') }}"
               placeholder="Nguyễn Văn A" required>
        @error('ten')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email sinh viên</label>
        <input type="email" id="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $sinhvien->email ?? '') }}"
               placeholder="sinhvien@st.huce.edu.vn">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại</label>
        <input type="text" id="so_dien_thoai" name="so_dien_thoai"
               class="form-control @error('so_dien_thoai') is-invalid @enderror"
               value="{{ old('so_dien_thoai', $sinhvien->so_dien_thoai ?? '') }}"
               placeholder="0912345678">
        @error('so_dien_thoai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="tuoi" class="form-label fw-semibold">Tuổi <span class="text-danger">*</span></label>
        <input type="number" id="tuoi" name="tuoi" min="16" max="100"
               class="form-control @error('tuoi') is-invalid @enderror"
               value="{{ old('tuoi', $sinhvien->tuoi ?? 20) }}" required>
        @error('tuoi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="gioi_tinh" class="form-label fw-semibold">Giới tính <span class="text-danger">*</span></label>
        <select id="gioi_tinh" name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
            <option value="Nam" @selected(old('gioi_tinh', $sinhvien->gioi_tinh ?? 'Nam') === 'Nam')>Nam</option>
            <option value="Nữ" @selected(old('gioi_tinh', $sinhvien->gioi_tinh ?? '') === 'Nữ')>Nữ</option>
            <option value="Khác" @selected(old('gioi_tinh', $sinhvien->gioi_tinh ?? '') === 'Khác')>Khác</option>
        </select>
        @error('gioi_tinh')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="ngay_sinh" class="form-label fw-semibold">Ngày sinh</label>
        <input type="date" id="ngay_sinh" name="ngay_sinh"
               class="form-control @error('ngay_sinh') is-invalid @enderror"
               value="{{ old('ngay_sinh', isset($sinhvien->ngay_sinh) ? ($sinhvien->ngay_sinh ? $sinhvien->ngay_sinh->format('Y-m-d') : '') : '') }}">
        @error('ngay_sinh')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="lop_hoc_id" class="form-label fw-semibold">Lớp học</label>
        <select id="lop_hoc_id" name="lop_hoc_id" class="form-select @error('lop_hoc_id') is-invalid @enderror">
            <option value="">-- Chưa xếp lớp --</option>
            @foreach ($lopHocs as $lop)
                <option value="{{ $lop->id }}" @selected(old('lop_hoc_id', $sinhvien->lop_hoc_id ?? '') == $lop->id)>
                    {{ $lop->ten_lop }} ({{ $lop->ma_lop }})
                </option>
            @endforeach
        </select>
        @error('lop_hoc_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="dia_chi" class="form-label fw-semibold">Địa chỉ / Quê quán</label>
        <input type="text" id="dia_chi" name="dia_chi"
               class="form-control @error('dia_chi') is-invalid @enderror"
               value="{{ old('dia_chi', $sinhvien->dia_chi ?? '') }}"
               placeholder="Hà Nội, Hải Phòng, Đà Nẵng...">
        @error('dia_chi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai" value="1"
                   @checked(old('trang_thai', $sinhvien->trang_thai ?? true))>
            <label class="form-check-label fw-semibold" for="trang_thai">
                Trạng thái: Đang theo học
            </label>
        </div>
    </div>
</div>
