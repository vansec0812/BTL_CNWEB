<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTamTruTamVangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nhan_khau_id' => 'required|integer|exists:nhan_khau,id',
            'loai' => 'required|in:tam_tru,tam_vang',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'dia_chi_cu_tru_thuc_te' => 'required_if:loai,tam_tru|nullable|string|max:500',
            'dia_chi_vang_mat' => 'required_if:loai,tam_vang|nullable|string|max:500',
            'ly_do' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Vui lòng chọn nhân khẩu.',
            'nhan_khau_id.exists' => 'Nhân khẩu được chọn không tồn tại.',
            'loai.required' => 'Loại khai báo là bắt buộc.',
            'loai.in' => 'Loại khai báo không hợp lệ.',
            'ngay_bat_dau.required' => 'Ngày bắt đầu là bắt buộc.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không đúng định dạng ngày.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không đúng định dạng ngày.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'dia_chi_cu_tru_thuc_te.required_if' => 'Địa chỉ cư trú thực tế là bắt buộc đối với tạm trú.',
            'dia_chi_vang_mat.required_if' => 'Địa chỉ vắng mặt (nơi đến) là bắt buộc đối với tạm vắng.',
        ];
    }
}
