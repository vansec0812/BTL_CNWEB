<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHoKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hoKhau = $this->route('hoKhau') ?? $this->route('ho_khau');
        $hoKhauId = is_object($hoKhau) ? $hoKhau->id : $hoKhau;

        return [
            'so_so_ho_khau' => 'required|string|max:50|unique:ho_khau,so_so_ho_khau,'.$hoKhauId,
            'ma_ho' => 'required|string|max:30|unique:ho_khau,ma_ho,'.$hoKhauId,
            'chu_ho_nhan_khau_id' => 'nullable|integer|exists:nhan_khau,id',
            'dia_chi_thuong_tru' => 'required|string|max:500',
            'thon_xom' => 'nullable|string|max:100',
            'phan_loai' => 'required|in:thuong_tru,tam_tru,tam_vang',
            'so_thanh_vien' => 'nullable|integer|min:0',
            'ngay_lap_so' => 'nullable|date',
            'ngay_cap_nhat' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,da_giai_the,chuyen_di',
        ];
    }

    public function messages(): array
    {
        return [
            'so_so_ho_khau.required' => 'Số sổ hộ khẩu là bắt buộc.',
            'so_so_ho_khau.unique' => 'Số sổ hộ khẩu này đã tồn tại.',
            'ma_ho.required' => 'Mã hộ là bắt buộc.',
            'ma_ho.unique' => 'Mã hộ này đã tồn tại.',
            'dia_chi_thuong_tru.required' => 'Địa chỉ thường trú là bắt buộc.',
            'phan_loai.required' => 'Phân loại hộ khẩu là bắt buộc.',
            'phan_loai.in' => 'Phân loại không hợp lệ (chỉ chấp nhận: thuong_tru, tam_tru, tam_vang).',
            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ (chỉ chấp nhận: hoat_dong, da_giai_the, chuyen_di).',
        ];
    }
}
