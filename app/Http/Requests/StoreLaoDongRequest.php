<?php

namespace App\Http\Requests;

use App\Models\LaoDong;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaoDongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nhan_khau_id' => 'required|integer|exists:nhan_khau,id|unique:lao_dong,nhan_khau_id',
            'trang_thai_lao_dong' => ['required', Rule::in(array_keys(LaoDong::TRANG_THAI_LAO_DONG))],
            'nghe_nghiep' => 'nullable|string|max:255',
            'loai_hinh_cong_viec' => ['nullable', Rule::in(array_keys(LaoDong::LOAI_HINH_CONG_VIEC))],
            'nganh_nghe' => ['nullable', Rule::in(array_keys(LaoDong::NGANH_NGHE))],
            'lam_viec_ngoai_tinh' => 'boolean',
            'xuat_khau_lao_dong' => 'boolean',
            'quoc_gia_lam_viec' => 'nullable|string|max:100',
            'ten_cong_ty_nuoc_ngoai' => 'nullable|string|max:255',
            'ngay_xuat_canh' => 'nullable|date',
            'ngay_het_hop_dong_nuoc_ngoai' => 'nullable|date|after_or_equal:ngay_xuat_canh',
            'tinh_thanh_lam_viec' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Vui lòng chọn nhân khẩu.',
            'nhan_khau_id.exists' => 'Nhân khẩu không tồn tại.',
            'nhan_khau_id.unique' => 'Nhân khẩu này đã có hồ sơ lao động.',
            'trang_thai_lao_dong.required' => 'Vui lòng chọn trạng thái lao động.',
            'trang_thai_lao_dong.in' => 'Trạng thái lao động không hợp lệ.',
            'loai_hinh_cong_viec.in' => 'Loại hình công việc không hợp lệ.',
            'nganh_nghe.in' => 'Ngành nghề không hợp lệ.',
            'ngay_het_hop_dong_nuoc_ngoai.after_or_equal' => 'Ngày hết hạn hợp đồng phải sau hoặc bằng ngày xuất cảnh.',
        ];
    }
}
