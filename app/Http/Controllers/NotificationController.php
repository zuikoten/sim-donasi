<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect()->back()
            ->with('success', 'Notifikasi ditandai sebagai telah dibaca.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('status_baca', false)
            ->update(['status_baca' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi ditandai sebagai telah dibaca.');
    }

    public function deleteAllRead()
    {
        // Hapus semua notifikasi milik user yang sudah dibaca
        Notification::where('user_id', auth()->id())
            ->where('status_baca', true)
            ->delete();

        return redirect()->back()
            ->with('success', 'Semua notifikasi yang sudah dibaca berhasil dihapus.');
    }


    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}
