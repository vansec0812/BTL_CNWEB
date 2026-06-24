<?php

namespace App\Http\Requests;

use App\Models\YTeNhanKhau;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreYTeNhanKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nhan_khau_id' => [
                'required',
                'integer',
                Rule::exists('nhan_khau', 'id')->whereNull('deleted_at'),
                'unique:y_te_nhan_khau,nhan_khau_id',
            ],
            'so_the_bhyt' => ['nullable', 'string', 'max:50'],
            'loai_bhyt' => ['required', Rule::in(array_keys(YTeNhanKhau::LOAI_BHYT))],
            'ngay_cap_the_bhyt' => ['nullable', 'date'],
            'ngay_het_han_the_bhyt' => ['nullable', 'date', 'after_or_equal:ngay_cap_the_bhyt'],
            'noi_dang_ky_kham_chua_benh' => ['nullable', 'string', 'max:255'],
            'hoan_thanh_tiem_chung_mo_rong' => ['boolean'],
            'lich_su_tiem_chung' => ['nullable', 'json'],
            'ghi_chu_suc_khoe' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Nhân khẩu liên kết là bắt buộc.',
            'nhan_khau_id.integer' => 'Mã nhân khẩu phải là số nguyên.',
            'nhan_khau_id.exists' => 'Nhân khẩu không tồn tại trên hệ thống.',
            'nhan_khau_id.unique' => 'Nhân khẩu này đã có hồ sơ y tế.',
            'so_the_bhyt.max' => 'Số thẻ BHYT không được vượt quá 50 ký tự.',
            'loai_bhyt.required' => 'Loại bảo hiểm y tế là bắt buộc.',
            'loai_bhyt.in' => 'Loại bảo hiểm y tế không hợp lệ.',
            'ngay_cap_the_bhyt.date' => 'Ngày cấp thẻ không đúng định dạng ngày.',
            'ngay_het_han_the_bhyt.date' => 'Ngày hết hạn thẻ không đúng định dạng ngày.',
            'ngay_het_han_the_bhyt.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày cấp thẻ.',
            'noi_dang_ky_kham_chua_benh.max' => 'Nơi đăng ký khám chữa bệnh không được vượt quá 255 ký tự.',
            'hoan_thanh_tiem_chung_mo_rong.boolean' => 'Hoàn thành tiêm chủng mở rộng phải là kiểu boolean.',
            'lich_su_tiem_chung.json' => 'Lịch sử tiêm chủng phải là định dạng JSON hợp lệ.',
        ];
    }
}
