<?php

namespace App\Http\Controllers;

use App\Models\CoSoVatChat;
use App\Models\ModuleRegistry;
use Illuminate\Http\Request;

class CoSoVatChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CoSoVatChat::query();

        // Xử lý bộ lọc
        if ($request->filled('search')) {
            $query->where('ten_cong_trinh', 'like', '%' . $request->search . '%')
                  ->orWhere('thon_xom', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('phan_loai')) {
            $query->where('phan_loai', $request->phan_loai);
        }
        if ($request->filled('tinh_trang')) {
            $query->where('tinh_trang', $request->tinh_trang);
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('co-so-vat-chat.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('dat-dai-ha-tang'),
            'records' => $records,
            'filters' => $request->all(),
            'stats' => $this->getStats(),
        ]);
    }

    /**
     * Get statistics for the dashboard cards.
     */
    private function getStats()
    {
        return [
            'tong_cong_trinh' => CoSoVatChat::count(),
            'tong_von_dau_tu' => CoSoVatChat::sum('kinh_phi_xay_dung'),
            'cong_trinh_xuong_cap' => CoSoVatChat::whereIn('tinh_trang', ['xuong_cap', 'can_sua_chua'])->count(),
        ];
    }
}
