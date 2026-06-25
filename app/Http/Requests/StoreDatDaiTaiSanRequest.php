<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatDaiTaiSanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chu_so_huu_nhan_khau_id' => 'required|exists:nhan_khau,id',
            'so_to_ban_do' => 'nullable|string|max:50',
            'so_thua_dat' => 'nullable|string|max:50',
            'so_gcn_qsdd' => 'nullable|string|max:100|unique:dat_dai_tai_san,so_gcn_qsdd',
            'loai_dat' => 'required|in:dat_tho_cu,dat_nong_nghiep,dat_lam_nghiep,dat_nuoi_trong_thuy_san,dat_kinh_doanh,khac',
            'dien_tich_m2' => 'required|numeric|min:0.01',
            'vi_tri_mo_ta' => 'nullable|string|max:500',
            'thon_xom' => 'nullable|string|max:100',
            'ngay_cap_gcn' => 'nullable|date',
            'ngay_het_han_gcn' => 'nullable|date|after_or_equal:ngay_cap_gcn',
            'trang_thai' => 'required|in:dang_su_dung,cho_thue,bi_tranh_chap,da_chuyen_nhuong,thu_hoi',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'chu_so_huu_nhan_khau_id.required' => 'Chủ sở hữu là bắt buộc.',
            'chu_so_huu_nhan_khau_id.exists' => 'Chủ sở hữu không tồn tại.',
            'so_to_ban_do.max' => 'Số tờ bản đồ không được vượt quá 50 ký tự.',
            'so_thua_dat.max' => 'Số thửa đất không được vượt quá 50 ký tự.',
            'so_gcn_qsdd.max' => 'Số Giấy chứng nhận QSDĐ không được vượt quá 100 ký tự.',
            'so_gcn_qsdd.unique' => 'Số Giấy chứng nhận QSDĐ đã tồn tại trên hệ thống.',
            'loai_dat.required' => 'Loại đất là bắt buộc.',
            'loai_dat.in' => 'Loại đất không hợp lệ.',
            'dien_tich_m2.required' => 'Diện tích đất (m2) là bắt buộc.',
            'dien_tich_m2.numeric' => 'Diện tích đất phải là số.',
            'dien_tich_m2.min' => 'Diện tích đất phải tối thiểu là 0.01 m2.',
            'vi_tri_mo_ta.max' => 'Vị trí mô tả không được vượt quá 500 ký tự.',
            'thon_xom.max' => 'Thôn/xóm không được vượt quá 100 ký tự.',
            'ngay_cap_gcn.date' => 'Ngày cấp giấy chứng nhận không đúng định dạng ngày.',
            'ngay_het_han_gcn.date' => 'Ngày hết hạn không đúng định dạng ngày.',
            'ngay_het_han_gcn.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày cấp giấy chứng nhận.',
            'trang_thai.required' => 'Trạng thái sử dụng là bắt buộc.',
            'trang_thai.in' => 'Trạng thái sử dụng không hợp lệ.',
        ];
    }
}
