<?php

namespace App\Http\Requests;

use App\Models\BaoTroXaHoi;
use App\Models\DoiTuongChinhSach;
use App\Models\DotTroCap;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDotTroCapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_dot' => ['required', 'string', 'max:255'],
            'mo_ta' => ['nullable', 'string'],
            'loai_tro_cap' => ['required', Rule::in(array_keys(DotTroCap::LOAI_TRO_CAP))],
            'gia_tri_quy_doi' => ['nullable', 'integer', 'min:0'],
            'nguon_kinh_phi' => ['nullable', 'string', 'max:255'],
            'ngay_bat_dau_cap_phat' => ['required', 'date'],
            'ngay_ket_thuc_cap_phat' => ['nullable', 'date', 'after_or_equal:ngay_bat_dau_cap_phat'],
            'trang_thai' => ['required', Rule::in(array_keys(DotTroCap::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
            'loai_bao_tro' => ['nullable', 'array'],
            'loai_bao_tro.*' => ['string', Rule::in(array_keys(BaoTroXaHoi::LOAI_BAO_TRO))],
            'loai_chinh_sach' => ['nullable', 'array'],
            'loai_chinh_sach.*' => ['string', Rule::in(array_keys(DoiTuongChinhSach::LOAI_CHINH_SACH))],
            'thon_xom' => ['nullable', 'array'],
            'thon_xom.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_dot.required' => 'Tên đợt trợ cấp là bắt buộc.',
            'ten_dot.max' => 'Tên đợt trợ cấp không được vượt quá 255 ký tự.',
            'loai_tro_cap.required' => 'Loại hình trợ cấp là bắt buộc.',
            'loai_tro_cap.in' => 'Loại hình trợ cấp không hợp lệ.',
            'gia_tri_quy_doi.integer' => 'Giá trị quy đổi phải là số nguyên.',
            'gia_tri_quy_doi.min' => 'Giá trị quy đổi không được nhỏ hơn 0.',
            'nguon_kinh_phi.max' => 'Nguồn kinh phí không được vượt quá 255 ký tự.',
            'ngay_bat_dau_cap_phat.required' => 'Ngày bắt đầu cấp phát là bắt buộc.',
            'ngay_bat_dau_cap_phat.date' => 'Ngày bắt đầu cấp phát không đúng định dạng ngày.',
            'ngay_ket_thuc_cap_phat.date' => 'Ngày kết thúc cấp phát không đúng định dạng ngày.',
            'ngay_ket_thuc_cap_phat.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'trang_thai.required' => 'Trạng thái đợt trợ cấp là bắt buộc.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
