<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTamTruTamVangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
            'dia_chi_cu_tru_thuc_te' => 'nullable|string|max:500',
            'dia_chi_vang_mat' => 'nullable|string|max:500',
            'ly_do' => 'nullable|string|max:500',
            'trang_thai' => 'required|in:dang_hieu_luc,da_het_han,da_huy',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ngay_bat_dau.required' => 'Ngày bắt đầu là bắt buộc.',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không đúng định dạng ngày.',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không đúng định dạng ngày.',
            'ngay_ket_thuc.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'dia_chi_cu_tru_thuc_te.max' => 'Địa chỉ cư trú thực tế không được vượt quá 500 ký tự.',
            'dia_chi_vang_mat.max' => 'Địa chỉ vắng mặt không được vượt quá 500 ký tự.',
            'ly_do.max' => 'Lý do không được vượt quá 500 ký tự.',
            'trang_thai.required' => 'Trạng thái khai báo là bắt buộc.',
            'trang_thai.in' => 'Trạng thái khai báo không hợp lệ.',
        ];
    }
}
