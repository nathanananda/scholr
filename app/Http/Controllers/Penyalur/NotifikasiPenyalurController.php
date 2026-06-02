<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiPenyalurController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())->latest();

        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('body', 'like', "%{$request->search}%");
            });
        }

        return view('penyalur.notifikasi', [
            'notifications' => $query->paginate(15),
            'unreadCount'   => Notification::where('user_id', auth()->id())->whereNull('read_at')->count(),
            'todayCount'    => Notification::where('user_id', auth()->id())->whereDate('created_at', today())->count(),
        ]);
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    public function dismiss($id)
    {
        Notification::where('id', $id)->where('user_id', auth()->id())->delete();
        return back();
    }
}
