<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNhanKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_khau_id' => 'required|integer|exists:ho_khau,id',
            'ho_ten' => 'required|string|max:255',
            'cccd_cmnd' => 'nullable|string|max:20|unique:nhan_khau,cccd_cmnd',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:nam,nu,khac',
            'dan_toc' => 'required|string|max:100',
            'ton_giao' => 'nullable|string|max:100',
            'que_quan' => 'nullable|string|max:500',
            'noi_sinh' => 'nullable|string|max:500',
            'trinh_do_hoc_van' => 'nullable|in:mu_chu,tieu_hoc,thcs,thpt,trung_cap,cao_dang,dai_hoc,sau_dai_hoc',
            'tinh_trang_hon_nhan' => 'required|in:doc_than,da_ket_hon,ly_hon,goa',
            'quan_he_chu_ho' => 'nullable|string|max:100',
            'la_chu_ho' => 'nullable|boolean',
            'co_tien_an' => 'nullable|boolean',
            'ghi_chu_tien_an' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,tam_tru,tam_vang,da_chuyen_di,da_mat',
            'ngay_dang_ky_khai_sinh' => 'nullable|date',
            'ngay_khai_tu' => 'nullable|date',
            'ngay_chuyen_di' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_khau_id.required' => 'Hộ khẩu liên kết là bắt buộc.',
            'ho_khau_id.exists' => 'Hộ khẩu không tồn tại trên hệ thống.',
            'ho_ten.required' => 'Họ và tên là bắt buộc.',
            'cccd_cmnd.unique' => 'Số CCCD/CMND/Định danh đã tồn tại.',
            'ngay_sinh.required' => 'Ngày sinh là bắt buộc.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng ngày.',
            'gioi_tinh.required' => 'Giới tính là bắt buộc.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'dan_toc.required' => 'Dân tộc là bắt buộc.',
            'tinh_trang_hon_nhan.required' => 'Tình trạng hôn nhân là bắt buộc.',
            'trang_thai.required' => 'Trạng thái nhân khẩu là bắt buộc.',
        ];
    }
}
