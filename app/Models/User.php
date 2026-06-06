<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'so_cccd',
        'gioi_tinh',
        'ngay_sinh',
        'so_dien_thoai',
        'chuc_vu',
        'dia_chi',
        'que_quan',
        'trang_thai',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ngay_sinh' => 'date',
        ];
    }

    public function lichSuCongViec(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LichSuCongViec::class, 'nguoi_cap_nhat_id');
    }

    public function ketNoiViecLam(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KetNoiViecLam::class, 'nguoi_phu_trach_id');
    }
}
