<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ClientApiController extends Controller
{
     // Menangani penambahan client baru dari modal form
     public function store(Request $request)
     {
         // Validasi input dari form
         $request->validate([
             'company' => 'required|string|max:50',
             'name' => 'required|string|max:250',
             'full_address' => 'required|string|max:250',
             'nomor_telep' => 'required|string|max:250',
             'email' => 'required|string|email|max:250|unique:clients,email',
         ]);
 
         // Membuat kode perusahaan (company_code) secara otomatis dari nama perusahaan
         $companyCode = strtoupper(substr($request->company, 0, 3));
 
         // Menyimpan data client baru ke database
         $client = Client::create([
             'company' => $request->company,
             'company_code' => $companyCode,
             'nama_purchasing' => $request->name,
             'alamat' => $request->full_address,
             'email' => $request->email,
             'nomor_telep' => $request->nomor_telep,
             'deletion_reason' => null,
             'deletion_requested' => false,
             'deletion_approved' => false,
         ]);
 
         return response()->json([
             'message' => 'Client created successfully',
             'client' => $client,
         ], 201);
     }
     
     public function requestDelete(Request $request, Client $client)
     {
         // Validasi input untuk alasan penghapusan
         $request->validate([
             'deletion_reason' => 'required|string|min:5',
         ]);
     
         // Mendapatkan user yang sedang login
         $user = auth()->user();
     
         // Jika user tidak terautentikasi
         if (!$user) {
             return response()->json(['message' => 'Unauthenticated.'], 401);
         }
     
         // Cek apakah client sudah di-request untuk dihapus
         if ($client->deletion_requested) {
             return response()->json(['message' => 'Deletion already requested'], 400);
         }
     
         // Tandai client sebagai pengajuan penghapusan dan simpan alasan
         if (!$client->update([
             'deletion_requested' => true,
             'deletion_reason' => $request->deletion_reason,
         ])) {
             return response()->json(['message' => 'Failed to update client deletion request'], 500);
         }
     
         // Menyimpan notifikasi baru dengan client_id
         $notification = Notification::create([
             'title' => 'Request Delete Prospect from ' . $user->username,
             'body' => 'Request delete Prospect from ' . $user->username . ' (' . $user->position . ')',
             'user' => $user->username,
             'position' => $user->position,
             'client_id' => $client->id,  // Simpan client_id
             'read_at' => null,
         ]);
     
         // Cek apakah notifikasi berhasil disimpan
         if (!$notification) {
             return response()->json(['message' => 'Failed to create notification'], 500);
         }
     
         // Log untuk memverifikasi bahwa notifikasi telah dibuat dengan client_id yang benar
         Log::info('Notification created', ['notification_id' => $notification->id, 'client_id' => $notification->client_id]);
     
         // Return informasi user yang mengajukan request penghapusan
         return response()->json([
             'message' => 'Deletion request submitted successfully',
             'requested_by' => $user->username,
             'position' => $user->position,
         ], 200);
     }
     

     public function showIndex()
     {
        $clients = Client::with([
            'addresses',
            'addresses.specs',
        ])->get();
        return response()->json($clients, 200);
     }
    public function index()
    {
        // Mengambil semua data client beserta relasi yang terkait melalui ClientAddress
        $clients = Client::with([
            'addresses',            // Relasi ke ClientAddress
            'addresses.specs',      // Relasi ke ClientSpec melalui ClientAddress
            'orders',               // Relasi ke ClientOrder melalui ClientAddress
            'orders.stock',         // Relasi ke Stock melalui ClientOrder
            'orders.details',       // Relasi ke ClientOrderDetail melalui ClientOrder
            'orders.payments',      // Relasi ke ClientPayment melalui ClientOrder
            'notifications'         // Relasi ke Notification
        ])->get();

        return response()->json($clients, 200);
    }

    /**
     * API untuk menampilkan data client berdasarkan ID beserta relasi yang terkait
     */
    public function show($id)
    {
        // Mencari data client berdasarkan ID beserta relasinya
        $client = Client::with([
            'addresses',            // Relasi ke ClientAddress
            'addresses.specs',      // Relasi ke ClientSpec melalui ClientAddress
            'orders',               // Relasi ke ClientOrder melalui ClientAddress
            'orders.stock',         // Relasi ke Stock melalui ClientOrder
            'orders.details',       // Relasi ke ClientOrderDetail melalui ClientOrder
            'orders.payments',      // Relasi ke ClientPayment melalui ClientOrder
            'notifications'         // Relasi ke Notification
        ])->find($id);

        // Jika client tidak ditemukan, kembalikan respon 404
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        // Jika client ditemukan, kembalikan data client beserta relasi
        return response()->json($client, 200);
    }

}

