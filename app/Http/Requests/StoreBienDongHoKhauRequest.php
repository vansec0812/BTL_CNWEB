<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBienDongHoKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $loai = $this->input('loai_bien_dong');

        $rules = [
            'loai_bien_dong' => 'required|in:tach_ho,nhap_ho,chuyen_di,chuyen_den',
            'ngay_bien_dong' => 'required|date',
            'ly_do' => 'nullable|string|max:500',
            'so_quyet_dinh' => 'nullable|string|max:100',
            'ghi_chu' => 'nullable|string',
        ];

        if ($loai === 'tach_ho') {
            $rules = array_merge($rules, [
                'ho_khau_nguon_id' => 'required|integer|exists:ho_khau,id',
                'so_so_ho_khau_moi' => 'required|string|max:50|unique:ho_khau,so_so_ho_khau',
                'ma_ho_moi' => 'required|string|max:30|unique:ho_khau,ma_ho',
                'dia_chi_thuong_tru_moi' => 'required|string|max:500',
                'thon_xom_moi' => 'nullable|string|max:100',
                'new_chu_ho_id' => 'required|integer|exists:nhan_khau,id',
                'thanh_vien_ids' => 'required|array|min:1',
                'thanh_vien_ids.*' => 'integer|exists:nhan_khau,id',
                'quan_he' => 'required|array',
            ]);
        } elseif ($loai === 'nhap_ho') {
            $rules = array_merge($rules, [
                'ho_khau_dich_id' => 'required|integer|exists:ho_khau,id',
                'nhan_khau_id' => 'required|integer|exists:nhan_khau,id',
                'quan_he_chu_ho' => 'required|string|max:100',
            ]);
        } elseif ($loai === 'chuyen_di') {
            $rules = array_merge($rules, [
                'nhan_khau_id' => 'required_without:ho_khau_id|nullable|integer|exists:nhan_khau,id',
                'ho_khau_id' => 'required_without:nhan_khau_id|nullable|integer|exists:ho_khau,id',
                'dia_chi_chuyen_den' => 'required|string|max:500',
            ]);
        } elseif ($loai === 'chuyen_den') {
            $rules = array_merge($rules, [
                'ho_khau_id' => 'required|integer|exists:ho_khau,id',
                'nhan_khau' => 'required|array',
                'nhan_khau.ho_ten' => 'required|string|max:255',
                'nhan_khau.cccd_cmnd' => 'nullable|string|max:20|unique:nhan_khau,cccd_cmnd',
                'nhan_khau.ngay_sinh' => 'required|date',
                'nhan_khau.gioi_tinh' => 'required|in:nam,nu,khac',
                'nhan_khau.dan_toc' => 'required|string|max:100',
                'nhan_khau.ton_giao' => 'nullable|string|max:100',
                'nhan_khau.que_quan' => 'nullable|string|max:500',
                'nhan_khau.noi_sinh' => 'nullable|string|max:500',
                'nhan_khau.trinh_do_hoc_van' => 'nullable|in:mu_chu,tieu_hoc,thcs,thpt,trung_cap,cao_dang,dai_hoc,sau_dai_hoc',
                'nhan_khau.tinh_trang_hon_nhan' => 'required|in:doc_than,da_ket_hon,ly_hon,goa',
                'nhan_khau.quan_he_chu_ho' => 'required|string|max:100',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'loai_bien_dong.required' => 'Loại biến động là bắt buộc.',
            'loai_bien_dong.in' => 'Loại biến động không hợp lệ.',
            'ngay_bien_dong.required' => 'Ngày thực hiện biến động là bắt buộc.',
            'ngay_bien_dong.date' => 'Ngày thực hiện biến động không đúng định dạng ngày.',
            'ho_khau_nguon_id.required' => 'Hộ khẩu nguồn là bắt buộc.',
            'ho_khau_nguon_id.exists' => 'Hộ khẩu nguồn không tồn tại.',
            'so_so_ho_khau_moi.required' => 'Số sổ hộ khẩu mới là bắt buộc.',
            'so_so_ho_khau_moi.unique' => 'Số sổ hộ khẩu mới đã tồn tại.',
            'ma_ho_moi.required' => 'Mã hộ mới là bắt buộc.',
            'ma_ho_moi.unique' => 'Mã hộ mới đã tồn tại.',
            'dia_chi_thuong_tru_moi.required' => 'Địa chỉ thường trú mới là bắt buộc.',
            'new_chu_ho_id.required' => 'Chủ hộ mới là bắt buộc.',
            'thanh_vien_ids.required' => 'Danh sách thành viên chuyển đi là bắt buộc.',
            'thanh_vien_ids.min' => 'Chọn ít nhất 1 thành viên chuyển đi.',
            'ho_khau_dich_id.required' => 'Hộ khẩu đích là bắt buộc.',
            'nhan_khau_id.required' => 'Nhân khẩu là bắt buộc.',
            'nhan_khau_id.required_without' => 'Vui lòng chọn nhân khẩu hoặc hộ khẩu chuyển đi.',
            'ho_khau_id.required_without' => 'Vui lòng chọn nhân khẩu hoặc hộ khẩu chuyển đi.',
            'dia_chi_chuyen_den.required' => 'Địa chỉ chuyển đến là bắt buộc.',
            'quan_he_chu_ho.required' => 'Quan hệ với chủ hộ là bắt buộc.',
            'nhan_khau.ho_ten.required' => 'Họ tên nhân khẩu chuyển đến là bắt buộc.',
            'nhan_khau.ngay_sinh.required' => 'Ngày sinh nhân khẩu chuyển đến là bắt buộc.',
            'nhan_khau.gioi_tinh.required' => 'Giới tính nhân khẩu chuyển đến là bắt buộc.',
            'nhan_khau.dan_toc.required' => 'Dân tộc nhân khẩu chuyển đến là bắt buộc.',
            'nhan_khau.tinh_trang_hon_nhan.required' => 'Tình trạng hôn nhân là bắt buộc.',
            'nhan_khau.quan_he_chu_ho.required' => 'Quan hệ của nhân khẩu với chủ hộ là bắt buộc.',
            'nhan_khau.cccd_cmnd.unique' => 'Số CCCD/CMND này đã tồn tại trên hệ thống.',
        ];
    }
}
