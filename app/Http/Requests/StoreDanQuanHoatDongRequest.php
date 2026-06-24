<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDanQuanHoatDongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dan_quan_tu_ve_id' => ['required', 'integer', 'exists:dan_quan_tu_ve,id'],
            'loai_hoat_dong' => ['required', 'string', 'in:tap_huan,truc_ban'],
            'ten_hoat_dong' => ['required', 'string', 'max:255'],
            'ngay_thuc_hien' => ['required', 'date'],
            'trang_thai' => ['required', 'string', 'in:tham_gia,vang_co_phep,vang_khong_phep,da_truc,vang_mat'],
            'ghi_chu' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'dan_quan_tu_ve_id.required' => 'Vui lòng chọn dân quân tự vệ.',
            'dan_quan_tu_ve_id.exists' => 'Dân quân tự vệ không hợp lệ.',
            'loai_hoat_dong.required' => 'Loại hoạt động không được để trống.',
            'loai_hoat_dong.in' => 'Loại hoạt động không hợp lệ.',
            'ten_hoat_dong.required' => 'Tên hoạt động không được để trống.',
            'ten_hoat_dong.max' => 'Tên hoạt động không được vượt quá 255 ký tự.',
            'ngay_thuc_hien.required' => 'Ngày thực hiện không được để trống.',
            'ngay_thuc_hien.date' => 'Ngày thực hiện không đúng định dạng ngày.',
            'trang_thai.required' => 'Trạng thái không được để trống.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
