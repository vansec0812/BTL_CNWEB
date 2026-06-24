<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait AuditableTrait
{
    public static function bootAuditableTrait(): void
    {
        static::created(function ($model) {
            $model->logActivity('create', null, $model->getAuditAttributes());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            $old = [];
            $new = [];

            foreach ($dirty as $key => $value) {
                if (in_array($key, ['updated_at', 'created_at'])) {
                    continue;
                }
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }

            if (! empty($new)) {
                $model->logActivity('update', $old, $new);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', $model->getAuditAttributes(), null);
        });
    }

    protected function logActivity(string $action, ?array $old, ?array $new): void
    {
        // Prevent infinite loop if logging on AuditLog itself
        if (get_class($this) === AuditLog::class) {
            return;
        }

        $user = Auth::user();
        $module = $this->getAuditModule();
        $mo_ta = $this->getAuditDescription($action);

        AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Hệ thống',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'action' => $action,
            'module' => $module,
            'model_class' => get_class($this),
            'model_id' => $this->id,
            'gia_tri_cu' => $old,
            'gia_tri_moi' => $new,
            'mo_ta' => $mo_ta,
        ]);
    }

    protected function getAuditModule(): string
    {
        if (property_exists($this, 'auditModule')) {
            return $this->auditModule;
        }

        return str_replace('_', '-', $this->getTable());
    }

    protected function getAuditDescription(string $action): string
    {
        $name = isset($this->ho_ten) ? $this->ho_ten : (isset($this->so_so_ho_khau) ? $this->so_so_ho_khau : (isset($this->ten_dot) ? $this->ten_dot : (isset($this->ten_doanh_nghiep) ? $this->ten_doanh_nghiep : 'Bản ghi #'.$this->id)));

        $actionLabel = [
            'create' => 'Thêm mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
        ][$action] ?? $action;

        return "$actionLabel bản ghi [$name] trong module ".$this->getAuditModule();
    }

    protected function getAuditAttributes(): array
    {
        $attributes = $this->toArray();
        unset($attributes['password'], $attributes['remember_token']);

        return $attributes;
    }
}
