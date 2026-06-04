<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userModel = $this->route('user');
        $userId = is_object($userModel) ? $userModel->id : $userModel;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:6'],
            'so_cccd' => ['nullable', 'string', 'digits:12', 'unique:users,so_cccd,' . $userId],
            'gioi_tinh' => ['required', 'string', 'in:nam,nu,khac'],
            'ngay_sinh' => ['nullable', 'date'],
            'so_dien_thoai' => ['nullable', 'string', 'max:20'],
            'chuc_vu' => ['nullable', 'string', 'max:255'],
            'dia_chi' => ['nullable', 'string', 'max:500'],
            'que_quan' => ['nullable', 'string', 'max:500'],
            'trang_thai' => ['required', 'string', 'in:active,inactive'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Họ tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trên hệ thống.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'so_cccd.digits' => 'Số CCCD phải đúng 12 chữ số.',
            'so_cccd.unique' => 'Số CCCD đã được đăng ký trên hệ thống.',
            'role.required' => 'Vai trò không được để trống.',
            'role.exists' => 'Vai trò được chọn không hợp lệ.',
        ];
    }
}
