<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBienDongHoKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ngay_bien_dong' => 'required|date',
            'so_quyet_dinh' => 'nullable|string|max:100',
            'ly_do' => 'nullable|string|max:500',
            'dia_chi_chuyen_den' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ngay_bien_dong.required' => 'Ngày thực hiện biến động là bắt buộc.',
            'ngay_bien_dong.date' => 'Ngày thực hiện biến động không đúng định dạng ngày.',
            'so_quyet_dinh.max' => 'Số quyết định không được vượt quá 100 ký tự.',
            'ly_do.max' => 'Lý do không được vượt quá 500 ký tự.',
            'dia_chi_chuyen_den.max' => 'Địa chỉ chuyển đến không được vượt quá 500 ký tự.',
        ];
    }
}
