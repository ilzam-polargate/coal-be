<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Mark all notifications as read and delete them
    public function markAllAsRead()
    {
        // Hapus semua notifikasi yang belum dibaca
        Notification::whereNull('read_at')->delete();

        // Kembalikan respons dengan jumlah notifikasi yang belum dibaca (harus nol)
        $unreadCount = 0;

        return response()->json(['message' => 'All notifications deleted', 'unreadCount' => $unreadCount]);
    }

    // Mark and delete a specific notification when clicked
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Hapus notifikasi yang di-klik
        $notification->delete();

        // Kembalikan respons dengan jumlah notifikasi yang belum dibaca
        $unreadCount = Notification::whereNull('read_at')->count();

        return response()->json(['message' => 'Notification deleted', 'unreadCount' => $unreadCount]);
    }
}
