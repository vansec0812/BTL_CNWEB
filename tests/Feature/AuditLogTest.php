<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\HoKhau;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles and permissions
        $viewPermission = Permission::findOrCreate('view_audit_logs', 'web');
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo($viewPermission);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->regularUser = User::factory()->create();
    }

    public function test_admin_can_view_audit_logs(): void
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'user_name' => $this->admin->name,
            'ip_address' => '127.0.0.1',
            'action' => 'login',
            'module' => 'he-thong',
            'mo_ta' => 'Admin logged in',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('audit-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Admin logged in');
    }

    public function test_admin_can_view_audit_logs_via_json_api(): void
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'user_name' => $this->admin->name,
            'ip_address' => '127.0.0.1',
            'action' => 'login',
            'module' => 'he-thong',
            'mo_ta' => 'Admin logged in API',
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('audit-logs.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data',
                    'current_page',
                ],
            ])
            ->assertJsonPath('success', true);
    }

    public function test_admin_can_view_single_audit_log_via_json_api(): void
    {
        $log = AuditLog::create([
            'user_id' => $this->admin->id,
            'user_name' => $this->admin->name,
            'ip_address' => '127.0.0.1',
            'action' => 'login',
            'module' => 'he-thong',
            'mo_ta' => 'Admin view single log',
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('audit-logs.show', $log->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'mo_ta',
                ],
            ])
            ->assertJsonPath('data.mo_ta', 'Admin view single log');
    }

    public function test_regular_user_cannot_view_audit_logs(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('audit-logs.index'));

        $response->assertStatus(403);
    }

    public function test_model_activity_is_automatically_logged(): void
    {
        // Active role for ho_khau management
        $managePermission = Permission::findOrCreate('manage_ho_khau', 'web');
        $this->admin->givePermissionTo($managePermission);

        $hoKhauData = [
            'so_so_ho_khau' => '123456789',
            'ma_ho' => 'HOKHAU001',
            'dia_chi_thuong_tru' => '123 Street Name',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ];

        // Perform creation via model directly
        $hoKhau = $this->actingAs($this->admin)->forceCreateCo(HoKhau::class, $hoKhauData);

        // Verify log was created
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'create',
            'module' => 'ho_khau',
            'model_id' => $hoKhau->id,
        ]);

        // Perform update via model
        $hoKhau->update(['dia_chi_thuong_tru' => 'New Address']);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'update',
            'module' => 'ho_khau',
            'model_id' => $hoKhau->id,
        ]);

        // Verify the old and new values are correctly logged
        $latestLog = AuditLog::where('action', 'update')->first();
        $this->assertEquals('123 Street Name', $latestLog->gia_tri_cu['dia_chi_thuong_tru']);
        $this->assertEquals('New Address', $latestLog->gia_tri_moi['dia_chi_thuong_tru']);
    }

    /**
     * Helper to force create a model bypass fillable if needed
     */
    private function forceCreateCo(string $modelClass, array $data)
    {
        $model = new $modelClass;
        $model->fill($data);
        $model->save();

        return $model;
    }
}
