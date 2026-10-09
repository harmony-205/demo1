@php($sinhvien = $sinhvien ?? null)

<div class="mb-3">
    <label for="ten" class="form-label">Tên sinh viên <span class="text-danger">*</span></label>
    <input type="text" id="ten" name="ten"
           class="form-control @error('ten') is-invalid @enderror"
           value="{{ old('ten', $sinhvien['ten'] ?? $sinhvien['name'] ?? '') }}"
           placeholder="Nhập họ và tên sinh viên">
    @error('ten')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="tuoi" class="form-label">Tuổi <span class="text-danger">*</span></label>
    <input type="number" id="tuoi" name="tuoi" min="1" max="120"
           class="form-control @error('tuoi') is-invalid @enderror"
           value="{{ old('tuoi', $sinhvien['tuoi'] ?? $sinhvien['age'] ?? '') }}"
           placeholder="Nhập tuổi sinh viên">
    @error('tuoi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="diachi" class="form-label">Địa chỉ</label>
    <input type="text" id="diachi" name="diachi"
           class="form-control @error('diachi') is-invalid @enderror"
           value="{{ old('diachi', $sinhvien['diachi'] ?? '') }}"
           placeholder="Ví dụ: Hà Nội, Hải Phòng...">
    @error('diachi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
