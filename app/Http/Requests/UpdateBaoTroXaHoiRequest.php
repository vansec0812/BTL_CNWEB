<?php

namespace App\Http\Requests;

use App\Models\BaoTroXaHoi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBaoTroXaHoiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $record = $this->route('bao_tro_xa_hoi') ?? $this->route('baoTroXaHoi');
        $currentHoKhauId = is_object($record) ? $record->ho_khau_id : null;
        $currentNhanKhauId = is_object($record) ? $record->nhan_khau_id : null;

        return [
            'loai_bao_tro' => ['required', Rule::in(array_keys(BaoTroXaHoi::LOAI_BAO_TRO))],
            'ho_khau_id' => [
                'nullable',
                'integer',
                Rule::exists('ho_khau', 'id')->where(function ($query) use ($currentHoKhauId) {
                    $query->whereNull('deleted_at')->where('trang_thai', 'hoat_dong');
                    if ($currentHoKhauId) {
                        $query->orWhere('id', $currentHoKhauId);
                    }
                }),
            ],
            'nhan_khau_id' => [
                'nullable',
                'integer',
                Rule::exists('nhan_khau', 'id')->where(function ($query) use ($currentNhanKhauId) {
                    $query->whereNull('deleted_at')->where('trang_thai', '!=', 'da_mat');
                    if ($currentNhanKhauId) {
                        $query->orWhere('id', $currentNhanKhauId);
                    }
                }),
            ],
            'muc_do_khuyet_tat' => ['required', Rule::in(array_keys(BaoTroXaHoi::MUC_DO_KHUYET_TAT))],
            'dang_khuyet_tat' => ['nullable', 'string', 'max:255'],
            'so_quyet_dinh' => ['nullable', 'string', 'max:100'],
            'ngay_bat_dau_huong' => ['nullable', 'date'],
            'ngay_ket_thuc_huong' => ['nullable', 'date', 'after_or_equal:ngay_bat_dau_huong'],
            'muc_tro_cap_hang_thang' => ['nullable', 'integer', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(BaoTroXaHoi::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $loaiBaoTro = $this->input('loai_bao_tro');
            $hoKhauId = $this->input('ho_khau_id');
            $nhanKhauId = $this->input('nhan_khau_id');
            $isHouseholdType = in_array($loaiBaoTro, BaoTroXaHoi::LOAI_THEO_HO, true);

            if ($isHouseholdType && empty($hoKhauId)) {
                $validator->errors()->add('ho_khau_id', 'Loại hộ nghèo/cận nghèo phải chọn sổ hộ khẩu.');
            }

            if (! $isHouseholdType && empty($nhanKhauId)) {
                $validator->errors()->add('nhan_khau_id', 'Loại bảo trợ cá nhân phải chọn nhân khẩu.');
            }

            if (! empty($hoKhauId) && ! empty($nhanKhauId)) {
                $validator->errors()->add('ho_khau_id', 'Mỗi hồ sơ chỉ được gắn với một hộ khẩu hoặc một nhân khẩu.');
                $validator->errors()->add('nhan_khau_id', 'Mỗi hồ sơ chỉ được gắn với một hộ khẩu hoặc một nhân khẩu.');
            }

            if ($loaiBaoTro === 'nguoi_khuyet_tat') {
                if (($this->input('muc_do_khuyet_tat') ?? 'khong_ap_dung') === 'khong_ap_dung') {
                    $validator->errors()->add('muc_do_khuyet_tat', 'Người khuyết tật phải chọn mức độ khuyết tật.');
                }

                if (blank($this->input('dang_khuyet_tat') ?? null)) {
                    $validator->errors()->add('dang_khuyet_tat', 'Người khuyết tật phải nhập dạng khuyết tật.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'loai_bao_tro.required' => 'Loại bảo trợ là bắt buộc.',
            'loai_bao_tro.in' => 'Loại bảo trợ không hợp lệ.',
            'ho_khau_id.integer' => 'Mã hộ khẩu phải là số nguyên.',
            'ho_khau_id.exists' => 'Hộ khẩu được chọn không tồn tại hoặc không ở trạng thái hoạt động.',
            'nhan_khau_id.integer' => 'Mã nhân khẩu phải là số nguyên.',
            'nhan_khau_id.exists' => 'Nhân khẩu được chọn không tồn tại hoặc đã mất.',
            'muc_do_khuyet_tat.required' => 'Mức độ khuyết tật là bắt buộc.',
            'muc_do_khuyet_tat.in' => 'Mức độ khuyết tật không hợp lệ.',
            'dang_khuyet_tat.max' => 'Dạng khuyết tật không được vượt quá 255 ký tự.',
            'so_quyet_dinh.max' => 'Số quyết định không được vượt quá 100 ký tự.',
            'ngay_bat_dau_huong.date' => 'Ngày bắt đầu hưởng không đúng định dạng ngày.',
            'ngay_ket_thuc_huong.date' => 'Ngày kết thúc hưởng không đúng định dạng ngày.',
            'ngay_ket_thuc_huong.after_or_equal' => 'Ngày kết thúc hưởng phải lớn hơn hoặc bằng ngày bắt đầu hưởng.',
            'muc_tro_cap_hang_thang.integer' => 'Mức trợ cấp hàng tháng phải là số nguyên.',
            'muc_tro_cap_hang_thang.min' => 'Mức trợ cấp hàng tháng không được nhỏ hơn 0.',
            'trang_thai.required' => 'Trạng thái hưởng là bắt buộc.',
            'trang_thai.in' => 'Trạng thái hưởng không hợp lệ.',
        ];
    }
}
