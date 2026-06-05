<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNhanKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nhanKhau = $this->route('nhan_khau') ?? $this->route('nhanKhau');
        $nhanKhauId = is_object($nhanKhau) ? $nhanKhau->id : $nhanKhau;

        return [
            'ho_khau_id' => 'required|integer|exists:ho_khau,id',
            'ho_ten' => 'required|string|max:255',
            'cccd_cmnd' => 'nullable|string|regex:/^[0-9]{9}$|^[0-9]{12}$/|unique:nhan_khau,cccd_cmnd,'.$nhanKhauId,
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
            'ho_khau_id.integer' => 'Mã hộ khẩu phải là số nguyên.',
            'ho_khau_id.exists' => 'Hộ khẩu không tồn tại trên hệ thống.',

            'ho_ten.required' => 'Họ và tên là bắt buộc.',
            'ho_ten.string' => 'Họ và tên phải là chuỗi ký tự.',
            'ho_ten.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'cccd_cmnd.regex' => 'Số CCCD/CMND/Mã định danh phải chứa đúng 9 hoặc 12 chữ số.',
            'cccd_cmnd.unique' => 'Số CCCD/CMND/Định danh đã tồn tại.',

            'ngay_sinh.required' => 'Ngày sinh là bắt buộc.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng ngày.',

            'gioi_tinh.required' => 'Giới tính là bắt buộc.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ (chỉ chấp nhận: nam, nữ, khác).',

            'dan_toc.required' => 'Dân tộc là bắt buộc.',
            'dan_toc.string' => 'Dân tộc phải là chuỗi ký tự.',
            'dan_toc.max' => 'Dân tộc không được vượt quá 100 ký tự.',

            'ton_giao.string' => 'Tôn giáo phải là chuỗi ký tự.',
            'ton_giao.max' => 'Tôn giáo không được vượt quá 100 ký tự.',

            'que_quan.string' => 'Quê quán phải là chuỗi ký tự.',
            'que_quan.max' => 'Quê quán không được vượt quá 500 ký tự.',

            'noi_sinh.string' => 'Nơi sinh phải là chuỗi ký tự.',
            'noi_sinh.max' => 'Nơi sinh không được vượt quá 500 ký tự.',

            'trinh_do_hoc_van.in' => 'Trình độ học vấn không hợp lệ.',

            'tinh_trang_hon_nhan.required' => 'Tình trạng hôn nhân là bắt buộc.',
            'tinh_trang_hon_nhan.in' => 'Tình trạng hôn nhân không hợp lệ (chỉ chấp nhận: độc thân, đã kết hôn, ly hôn, góa).',

            'quan_he_chu_ho.string' => 'Quan hệ với chủ hộ phải là chuỗi ký tự.',
            'quan_he_chu_ho.max' => 'Quan hệ với chủ hộ không được vượt quá 100 ký tự.',

            'la_chu_ho.boolean' => 'Lựa chọn chủ hộ phải là kiểu boolean.',
            'co_tien_an.boolean' => 'Tiền án/tiền sự phải là kiểu boolean.',
            'ghi_chu_tien_an.string' => 'Ghi chú tiền án phải là chuỗi ký tự.',

            'trang_thai.required' => 'Trạng thái nhân khẩu là bắt buộc.',
            'trang_thai.in' => 'Trạng thái cư trú không hợp lệ (chỉ chấp nhận: thường trú, tạm trú, tạm vắng, đã chuyển đi, đã mất).',

            'ngay_dang_ky_khai_sinh.date' => 'Ngày đăng ký khai sinh không đúng định dạng ngày.',
            'ngay_khai_tu.date' => 'Ngày khai tử không đúng định dạng ngày.',
            'ngay_chuyen_di.date' => 'Ngày chuyển đi không đúng định dạng ngày.',
            'ghi_chu.string' => 'Ghi chú phải là chuỗi ký tự.',
        ];
    }
}
