<?php

namespace App\Http\Requests;

use App\Models\KetNoiViecLam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKetNoiViecLamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $laoDongId = $this->input('lao_dong_id');
        $doanhNghiepId = $this->input('doanh_nghiep_id');
        $ngayKetNoi = $this->input('ngay_ket_noi');

        return [
            'lao_dong_id' => [
                'required',
                'integer',
                'exists:lao_dong,id',
                function ($attribute, $value, $fail) {
                    $laoDong = \App\Models\LaoDong::find($value);
                    if ($laoDong && $laoDong->trang_thai_lao_dong !== 'that_nghiep') {
                        // Vẫn cho phép kết nối nhưng cảnh báo hoặc ngăn chặn tùy nghiệp vụ. Hãy cho phép kết nối, nhưng thường là người thất nghiệp.
                        // Để kiểm tra chuẩn xác, ta kiểm tra nếu đang kết nối ở dạng chờ phản hồi
                        $exists = KetNoiViecLam::where('lao_dong_id', $value)
                            ->where('doanh_nghiep_id', $this->input('doanh_nghiep_id'))
                            ->where('ket_qua', 'dang_cho_phan_hoi')
                            ->exists();
                        if ($exists) {
                            $fail('Người lao động này đã có kết nối đang chờ phản hồi với doanh nghiệp này.');
                        }
                    }
                }
            ],
            'doanh_nghiep_id' => 'required|integer|exists:doanh_nghiep_ho_kinh_doanh,id',
            'ngay_ket_noi' => [
                'required',
                'date',
                Rule::unique('ket_noi_viec_lam')->where(function ($query) use ($laoDongId, $doanhNghiepId, $ngayKetNoi) {
                    return $query->where('lao_dong_id', $laoDongId)
                        ->where('doanh_nghiep_id', $doanhNghiepId)
                        ->where('ngay_ket_noi', $ngayKetNoi);
                }),
            ],
            'vi_tri_gioi_thieu' => 'nullable|string|max:255',
            'ket_qua' => ['required', Rule::in(array_keys(KetNoiViecLam::KET_QUA))],
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'lao_dong_id.required' => 'Người lao động là bắt buộc.',
            'doanh_nghiep_id.required' => 'Doanh nghiệp là bắt buộc.',
            'ngay_ket_noi.required' => 'Ngày kết nối là bắt buộc.',
            'ngay_ket_noi.unique' => 'Kết nối giữa người lao động và doanh nghiệp này vào ngày đã chọn đã tồn tại.',
            'ket_qua.required' => 'Kết quả kết nối là bắt buộc.',
            'ket_qua.in' => 'Kết quả kết nối không hợp lệ.',
        ];
    }
}
