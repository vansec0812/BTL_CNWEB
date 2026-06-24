<?php

namespace App\Http\Requests;

use App\Models\DoiTuongChinhSach;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoiTuongChinhSachRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $record = $this->route('doi_tuong_chinh_sach') ?? $this->route('doiTuongChinhSach');
        $id = is_object($record) ? $record->id : $record;
        $currentNhanKhauId = is_object($record) ? $record->nhan_khau_id : null;

        return [
            'nhan_khau_id' => [
                'required',
                'integer',
                Rule::exists('nhan_khau', 'id')->where(function ($query) use ($currentNhanKhauId) {
                    $query->whereNull('deleted_at')->where('trang_thai', '!=', 'da_mat');
                    if ($currentNhanKhauId) {
                        $query->orWhere('id', $currentNhanKhauId);
                    }
                }),
                'unique:doi_tuong_chinh_sach,nhan_khau_id,'.$id,
            ],
            'loai_chinh_sach' => ['required', Rule::in(array_keys(DoiTuongChinhSach::LOAI_CHINH_SACH))],
            'so_quyet_dinh_cong_nhan' => ['nullable', 'string', 'max:100'],
            'ngay_cong_nhan' => ['nullable', 'date'],
            'co_quan_cap' => ['nullable', 'string', 'max:255'],
            'ty_le_thuong_tat' => ['nullable', 'numeric', 'between:0,100'],
            'muc_tro_cap_hang_thang' => ['nullable', 'integer', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(DoiTuongChinhSach::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Nhân khẩu là bắt buộc.',
            'nhan_khau_id.integer' => 'Mã nhân khẩu phải là số nguyên.',
            'nhan_khau_id.exists' => 'Nhân khẩu được chọn không tồn tại hoặc đã mất.',
            'nhan_khau_id.unique' => 'Nhân khẩu này đã được đăng ký đối tượng chính sách.',
            'loai_chinh_sach.required' => 'Loại diện chính sách là bắt buộc.',
            'loai_chinh_sach.in' => 'Loại diện chính sách không hợp lệ.',
            'so_quyet_dinh_cong_nhan.max' => 'Số quyết định công nhận không được vượt quá 100 ký tự.',
            'ngay_cong_nhan.date' => 'Ngày công nhận không đúng định dạng ngày.',
            'co_quan_cap.max' => 'Cơ quan cấp không được vượt quá 255 ký tự.',
            'ty_le_thuong_tat.numeric' => 'Tỷ lệ thương tật phải là số.',
            'ty_le_thuong_tat.between' => 'Tỷ lệ thương tật phải nằm trong khoảng từ 0% đến 100%.',
            'muc_tro_cap_hang_thang.integer' => 'Mức trợ cấp hàng tháng phải là số nguyên.',
            'muc_tro_cap_hang_thang.min' => 'Mức trợ cấp hàng tháng không được nhỏ hơn 0.',
            'trang_thai.required' => 'Trạng thái là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
