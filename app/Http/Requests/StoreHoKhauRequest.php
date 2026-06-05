<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHoKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'so_so_ho_khau' => 'required|string|max:50|unique:ho_khau,so_so_ho_khau',
            'ma_ho' => 'required|string|max:30|unique:ho_khau,ma_ho',
            'chu_ho_nhan_khau_id' => 'nullable|integer|exists:nhan_khau,id',
            'dia_chi_thuong_tru' => 'required|string|max:500',
            'thon_xom' => 'nullable|string|max:100',
            'phan_loai' => 'required|in:thuong_tru,tam_tru,tam_vang',
            'so_thanh_vien' => 'nullable|integer|min:0',
            'ngay_lap_so' => 'nullable|date',
            'ngay_cap_nhat' => 'nullable|date',
            'ghi_chu' => 'nullable|string',
            'trang_thai' => 'required|in:hoat_dong,da_giai_the,chuyen_di',
        ];
    }

    public function messages(): array
    {
        return [
            'so_so_ho_khau.required' => 'Số sổ hộ khẩu là bắt buộc.',
            'so_so_ho_khau.string' => 'Số sổ hộ khẩu phải là chuỗi ký tự.',
            'so_so_ho_khau.max' => 'Số sổ hộ khẩu không được vượt quá 50 ký tự.',
            'so_so_ho_khau.unique' => 'Số sổ hộ khẩu này đã tồn tại.',

            'ma_ho.required' => 'Mã hộ là bắt buộc.',
            'ma_ho.string' => 'Mã hộ phải là chuỗi ký tự.',
            'ma_ho.max' => 'Mã hộ không được vượt quá 30 ký tự.',
            'ma_ho.unique' => 'Mã hộ này đã tồn tại.',

            'chu_ho_nhan_khau_id.integer' => 'Mã chủ hộ phải là số nguyên.',
            'chu_ho_nhan_khau_id.exists' => 'Nhân khẩu được chọn làm chủ hộ không tồn tại trên hệ thống.',

            'dia_chi_thuong_tru.required' => 'Địa chỉ thường trú là bắt buộc.',
            'dia_chi_thuong_tru.string' => 'Địa chỉ thường trú phải là chuỗi ký tự.',
            'dia_chi_thuong_tru.max' => 'Địa chỉ thường trú không được vượt quá 500 ký tự.',

            'thon_xom.string' => 'Thôn/Xóm/Đội phải là chuỗi ký tự.',
            'thon_xom.max' => 'Thôn/Xóm/Đội không được vượt quá 100 ký tự.',

            'phan_loai.required' => 'Phân loại hộ khẩu là bắt buộc.',
            'phan_loai.in' => 'Phân loại không hợp lệ (chỉ chấp nhận: thường trú, tạm trú, tạm vắng).',

            'so_thanh_vien.integer' => 'Số thành viên phải là số nguyên.',
            'so_thanh_vien.min' => 'Số thành viên không được nhỏ hơn 0.',

            'ngay_lap_so.date' => 'Ngày lập sổ không đúng định dạng ngày.',
            'ngay_cap_nhat.date' => 'Ngày cập nhật không đúng định dạng ngày.',

            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',

            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ (chỉ chấp nhận: đang hoạt động, đã giải thể, chuyển đi).',
        ];
    }
}
