<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        // General search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('mo_ta', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        $users = User::all();

        $actions = [
            'create' => 'Thêm mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'export' => 'Xuất dữ liệu',
        ];

        $logModules = AuditLog::distinct()->pluck('module')->filter()->all();
        $modules = ModuleRegistry::all();

        $parentModule = [
            'slug' => 'he-thong-bao-cao',
            'title' => 'Hệ thống, Tiện ích & Báo cáo',
        ];

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách nhật ký thao tác thành công.',
                'data' => $logs,
            ], 200);
        }

        return view('he-thong.audit-logs.index', compact('logs', 'users', 'actions', 'logModules', 'modules', 'parentModule'));
    }

    public function show(AuditLog $auditLog, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết nhật ký thao tác thành công.',
                'data' => $auditLog->load('user'),
            ], 200);
        }

        $modules = ModuleRegistry::all();
        $parentModule = [
            'slug' => 'he-thong-bao-cao',
            'title' => 'Hệ thống, Tiện ích & Báo cáo',
        ];

        return view('he-thong.audit-logs.show', compact('auditLog', 'modules', 'parentModule'));
    }
}
