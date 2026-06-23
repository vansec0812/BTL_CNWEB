<?php

namespace App\Http\Controllers;

use App\Models\CoSoVatChat;
use App\Support\ModuleRegistry;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $danhSachThon = \App\Models\HoKhau::select('thon_xom')->distinct()->whereNotNull('thon_xom')->pluck('thon_xom');
        
        return view('co-so-vat-chat.create', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('dat-dai-ha-tang'),
            'danhSachThon' => $danhSachThon,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_cong_trinh' => 'required|string|max:255',
            'phan_loai' => 'required|string|in:' . implode(',', array_keys(CoSoVatChat::PHAN_LOAI)),
            'thon_xom' => 'nullable|string|max:100',
            'ngay_dua_vao_su_dung' => 'nullable|date',
            'kinh_phi_xay_dung' => 'nullable|numeric|min:0',
            'tinh_trang' => 'required|string|in:' . implode(',', array_keys(CoSoVatChat::TINH_TRANG)),
            'ghi_chu' => 'nullable|string',
        ]);

        CoSoVatChat::create($validated);

        return redirect()->route('co-so-vat-chat.index')->with('success', 'Đã thêm mới công trình thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoSoVatChat $coSoVatChat)
    {
        $danhSachThon = \App\Models\HoKhau::select('thon_xom')->distinct()->whereNotNull('thon_xom')->pluck('thon_xom');
        
        return view('co-so-vat-chat.edit', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('dat-dai-ha-tang'),
            'record' => $coSoVatChat,
            'danhSachThon' => $danhSachThon,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoSoVatChat $coSoVatChat)
    {
        $validated = $request->validate([
            'ten_cong_trinh' => 'required|string|max:255',
            'phan_loai' => 'required|string|in:' . implode(',', array_keys(CoSoVatChat::PHAN_LOAI)),
            'thon_xom' => 'nullable|string|max:100',
            'ngay_dua_vao_su_dung' => 'nullable|date',
            'kinh_phi_xay_dung' => 'nullable|numeric|min:0',
            'tinh_trang' => 'required|string|in:' . implode(',', array_keys(CoSoVatChat::TINH_TRANG)),
            'ghi_chu' => 'nullable|string',
        ]);

        $coSoVatChat->update($validated);

        return redirect()->route('co-so-vat-chat.index')->with('success', 'Đã cập nhật công trình thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoSoVatChat $coSoVatChat)
    {
        $coSoVatChat->delete();
        return redirect()->route('co-so-vat-chat.index')->with('success', 'Đã xóa công trình thành công.');
    }
}
