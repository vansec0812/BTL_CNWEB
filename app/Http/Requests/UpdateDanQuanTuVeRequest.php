<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDanQuanTuVeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $militiaModel = $this->route('dan_quan_tu_ve');
        $militiaId = is_object($militiaModel) ? $militiaModel->id : $militiaModel;

        return [
            'nhan_khau_id' => ['required', 'integer', 'exists:nhan_khau,id', 'unique:dan_quan_tu_ve,nhan_khau_id,' . $militiaId],
            'chuc_vu' => ['nullable', 'string', 'in:' . implode(',', \App\Models\DanQuanTuVe::CHUC_VU_LIST)],
            'don_vi' => ['nullable', 'string', 'max:255'],
            'ngay_gia_nhap' => ['nullable', 'date'],
            'ngay_ket_thuc' => ['nullable', 'date', 'after:ngay_gia_nhap'],
            'trang_thai' => ['required', 'string', 'in:dang_phuc_vu,da_hoan_thanh,da_roi'],
            'ghi_chu' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Nhân khẩu không được để trống.',
            'nhan_khau_id.exists' => 'Nhân khẩu được chọn không hợp lệ.',
            'nhan_khau_id.unique' => 'Nhân khẩu này đã có trong danh sách Dân quân tự vệ.',
            'chuc_vu.in' => 'Chức vụ trong lực lượng không hợp lệ.',
            'ngay_ket_thuc.after' => 'Ngày kết thúc nhiệm kỳ phải sau ngày gia nhập.',
            'trang_thai.required' => 'Trạng thái không được để trống.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
