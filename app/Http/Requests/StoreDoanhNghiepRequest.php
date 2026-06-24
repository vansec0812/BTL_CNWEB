<?php

namespace App\Http\Requests;

use App\Models\DoanhNghiep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDoanhNghiepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_co_so' => 'required|string|max:255',
            'ma_so_thue' => 'nullable|string|max:50|unique:doanh_nghiep_ho_kinh_doanh,ma_so_thue',
            'ma_so_dang_ky_kinh_doanh' => 'nullable|string|max:100',
            'loai_hinh' => ['required', Rule::in(array_keys(DoanhNghiep::LOAI_HINH))],
            'nganh_nghe_chinh' => 'nullable|string|max:255',
            'dia_chi' => 'nullable|string|max:500',
            'thon_xom' => 'required|string|max:100',
            'nguoi_dai_dien_nhan_khau_id' => 'nullable|integer|exists:nhan_khau,id',
            'ten_nguoi_dai_dien' => 'nullable|string|max:255',
            'so_dien_thoai_lien_he' => 'nullable|string|max:20',
            'ngay_thanh_lap' => 'nullable|date',
            'so_lao_dong_hien_tai' => 'nullable|integer|min:0',
            'so_vi_tri_tuyen_dung' => 'nullable|integer|min:0',
            'trang_thai' => ['required', Rule::in(array_keys(DoanhNghiep::TRANG_THAI))],
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_co_so.required' => 'Tên cơ sở kinh doanh là bắt buộc.',
            'ma_so_thue.unique' => 'Mã số thuế này đã tồn tại trên hệ thống.',
            'loai_hinh.required' => 'Loại hình cơ sở kinh doanh là bắt buộc.',
            'loai_hinh.in' => 'Loại hình kinh doanh không hợp lệ.',
            'thon_xom.required' => 'Thôn/Xóm địa bàn xã là bắt buộc.',
            'trang_thai.required' => 'Trạng thái hoạt động là bắt buộc.',
            'trang_thai.in' => 'Trạng thái hoạt động không hợp lệ.',
            'nguoi_dai_dien_nhan_khau_id.exists' => 'Người đại diện không tồn tại trong danh sách nhân khẩu.',
        ];
    }
}
