<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThueVaPhiDiaPhuongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_khau_id' => 'required|exists:ho_khau,id',
            'nam' => 'required|integer|min:2000|max:2100',
            'loai_khoan_thu' => 'required|in:thue_dat_phi_nong_nghiep,phi_ve_sinh_moi_truong,quy_khuyen_hoc,phi_xay_dung_nong_thon_moi,phi_an_ninh_trat_tu,khac',
            'so_tien_phai_nop' => 'required|numeric|min:0',
            'so_tien_da_nop' => 'required|numeric|min:0',
            'han_nop' => 'nullable|date',
            'ghi_chu' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_khau_id.required' => 'Hộ khẩu liên kết là bắt buộc.',
            'ho_khau_id.exists' => 'Hộ khẩu không tồn tại trên hệ thống.',
            'nam.required' => 'Năm thu là bắt buộc.',
            'nam.integer' => 'Năm phải là số nguyên.',
            'nam.min' => 'Năm thu không được nhỏ hơn 2000.',
            'nam.max' => 'Năm thu không được lớn hơn 2100.',
            'loai_khoan_thu.required' => 'Loại khoản thu là bắt buộc.',
            'loai_khoan_thu.in' => 'Loại khoản thu không hợp lệ.',
            'so_tien_phai_nop.required' => 'Số tiền phải nộp là bắt buộc.',
            'so_tien_phai_nop.numeric' => 'Số tiền phải nộp phải là số.',
            'so_tien_phai_nop.min' => 'Số tiền phải nộp không được nhỏ hơn 0.',
            'so_tien_da_nop.required' => 'Số tiền đã nộp là bắt buộc.',
            'so_tien_da_nop.numeric' => 'Số tiền đã nộp phải là số.',
            'so_tien_da_nop.min' => 'Số tiền đã nộp không được nhỏ hơn 0.',
            'han_nop.date' => 'Hạn nộp không đúng định dạng ngày.',
            'ghi_chu.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ];
    }
}
