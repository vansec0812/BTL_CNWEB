<?php

namespace App\Http\Requests;

use App\Models\AnNinhTratTu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnNinhTratTuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nhan_khau_id' => [
                'nullable',
                'integer',
                Rule::exists('nhan_khau', 'id')->whereNull('deleted_at'),
            ],
            'ho_ten' => [
                'required',
                'string',
                'max:255',
            ],
            'cccd' => [
                'nullable',
                'string',
                'max:20',
            ],
            'dia_chi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'nhom_doi_tuong' => ['required', Rule::in(['vi_pham_hanh_chinh', 'quan_ly_dac_biet'])],
            'loai_doi_tuong' => ['required', 'string', 'max:100'],
            'co_quan_giai_quyet' => ['required', 'string', 'max:255'],
            'ngay_ghi_nhan' => ['required', 'date'],
            'noi_dung' => ['required', 'string'],
            'hinh_thuc_xu_ly' => ['nullable', 'string', 'max:255'],
            'so_tien_phat' => ['nullable', 'numeric', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(AnNinhTratTu::TRANG_THAI))],
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.integer' => 'Mã nhân khẩu phải là số nguyên.',
            'nhan_khau_id.exists' => 'Nhân khẩu không tồn tại trên hệ thống.',
            'ho_ten.required' => 'Họ tên là bắt buộc.',
            'ho_ten.string' => 'Họ tên phải là chuỗi ký tự.',
            'ho_ten.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'cccd.max' => 'Số CCCD không được vượt quá 20 ký tự.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'nhom_doi_tuong.required' => 'Nhóm đối tượng là bắt buộc.',
            'nhom_doi_tuong.in' => 'Nhóm đối tượng không hợp lệ.',
            'loai_doi_tuong.required' => 'Loại đối tượng là bắt buộc.',
            'loai_doi_tuong.string' => 'Loại đối tượng phải là chuỗi ký tự.',
            'loai_doi_tuong.max' => 'Loại đối tượng không được vượt quá 100 ký tự.',
            'co_quan_giai_quyet.required' => 'Cơ quan giải quyết là bắt buộc.',
            'co_quan_giai_quyet.string' => 'Cơ quan giải quyết phải là chuỗi ký tự.',
            'co_quan_giai_quyet.max' => 'Cơ quan giải quyết không được vượt quá 255 ký tự.',
            'ngay_ghi_nhan.required' => 'Ngày ghi nhận là bắt buộc.',
            'ngay_ghi_nhan.date' => 'Ngày ghi nhận không đúng định dạng ngày.',
            'noi_dung.required' => 'Nội dung vụ việc là bắt buộc.',
            'noi_dung.string' => 'Nội dung vụ việc phải là chuỗi ký tự.',
            'hinh_thuc_xu_ly.max' => 'Hình thức xử lý không được vượt quá 255 ký tự.',
            'so_tien_phat.numeric' => 'Số tiền phạt phải là số.',
            'so_tien_phat.min' => 'Số tiền phạt không được nhỏ hơn 0.',
            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
