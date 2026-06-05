<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNghiaVuQuanSuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nvqs = $this->route('nghia_vu_quan_su');
        $id = is_object($nvqs) ? $nvqs->id : $nvqs;

        return [
            'nhan_khau_id' => 'required|integer|exists:nhan_khau,id|unique:nghia_vu_quan_su,nhan_khau_id,'.$id,
            'nam_tuoi_tuyen_quan' => 'nullable|integer|min:1900|max:2100',
            'trang_thai_nvqs' => 'nullable|in:chua_den_tuoi,du_dieu_kien,tam_hoan,mien_goi,trung_tuyen,da_nhap_ngu,xuat_ngu,da_qua_tuoi',
            'ly_do_tam_hoan' => 'nullable|in:di_hoc_dai_hoc,benh_tat_suc_khoe,con_mot_con,nuoi_duong_than_nhan,ly_do_khac,khong_ap_dung',
            'ngay_tam_hoan_den' => 'nullable|date',
            'ngay_nhap_ngu' => 'nullable|date',
            'don_vi_quan_doi' => 'nullable|string|max:255',
            'ngay_xuat_ngu' => 'nullable|date|after_or_equal:ngay_nhap_ngu',
            'quan_ham_khi_xuat_ngu' => 'nullable|string|max:100',
            'nam_dang_ky_kham_nvqs' => 'nullable|integer|min:1900|max:2100',
            'ket_qua_kham_suc_khoe' => 'nullable|in:chua_kham,loai_1,loai_2,loai_3,loai_4,loai_5,khong_du_suc_khoe',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nhan_khau_id.required' => 'Nhân khẩu là bắt buộc.',
            'nhan_khau_id.exists' => 'Nhân khẩu không tồn tại trên hệ thống.',
            'nhan_khau_id.unique' => 'Nhân khẩu này đã có hồ sơ nghĩa vụ quân sự.',
            'nam_tuoi_tuyen_quan.integer' => 'Năm tuổi tuyển quân phải là số nguyên.',
            'trang_thai_nvqs.in' => 'Trạng thái nghĩa vụ quân sự không hợp lệ.',
            'ly_do_tam_hoan.in' => 'Lý do tạm hoãn không hợp lệ.',
            'ngay_tam_hoan_den.date' => 'Ngày tạm hoãn đến phải là ngày hợp lệ.',
            'ngay_nhap_ngu.date' => 'Ngày nhập ngũ phải là ngày hợp lệ.',
            'ngay_xuat_ngu.date' => 'Ngày xuất ngũ phải là ngày hợp lệ.',
            'ngay_xuat_ngu.after_or_equal' => 'Ngày xuất ngũ phải sau hoặc bằng ngày nhập ngũ.',
            'ket_qua_kham_suc_khoe.in' => 'Kết quả khám sức khỏe không hợp lệ.',
        ];
    }
}
