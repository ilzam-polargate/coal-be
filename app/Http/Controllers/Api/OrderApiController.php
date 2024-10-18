<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientOrderDetail;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    /**
     * Menampilkan semua order detail beserta informasi client, order, payments, dan client_spec.
     */
    public function index()
    {
        // Mengambil data order detail beserta relasi terkait
        $orderDetails = ClientOrderDetail::with([
            'clientOrder',                     // Mengambil informasi client order terkait
            'clientOrder.clientAddress',       // Mengambil informasi alamat client terkait
            'clientOrder.clientAddress.client', // Mengambil informasi client terkait
            'clientOrder.payments',            // Mengambil semua pembayaran terkait order
            'clientOrder.clientSpec'           // Mengambil spesifikasi client terkait order
        ])->get();

        // Mengubah data agar hanya mengirimkan informasi yang diperlukan
        $formattedOrderDetails = $orderDetails->map(function ($detail) {
            // Mengambil pembayaran yang terkait dengan detail order ini
            $payment = $detail->clientOrder->payments->where('client_order_detail_id', $detail->id)->first();

            return [
                'detail_id' => $detail->id,
                'client_order_id' => $detail->client_order_id,
                'no_po' => $detail->no_po,
                'jumlah_order' => $detail->jumlah_order,
                'status' => $detail->status,
                'image' => $detail->image,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
                'payment' => $payment ? [
                    'id' => $payment->id,
                    'termin_ke' => $payment->termin_ke,
                    'jumlah_bayar' => $payment->jumlah_bayar,
                    'tgl_jatuh_tempo' => $payment->tgl_jatuh_tempo,
                    'tgl_bayar' => $payment->tgl_bayar,
                    'payment_status' => $payment->payment_status,
                    'keterangan' => $payment->keterangan
                ] : null, // Jika tidak ada pembayaran terkait, set null
                'client_order' => [
                    'order_id' => $detail->clientOrder->id,
                    'no_po' => $detail->clientOrder->no_po,
                    'status_order' => $detail->clientOrder->status_order,
                    'order_date' => $detail->clientOrder->order_date,
                    'total_order' => $detail->clientOrder->total_order,
                    'keterangan' => $detail->clientOrder->keterangan,
                    'client_address' => $detail->clientOrder->clientAddress ? [
                        'penerima' => $detail->clientOrder->clientAddress->penerima,
                        'alamat_lengkap' => $detail->clientOrder->clientAddress->alamat_lengkap,
                        'nama_cp' => $detail->clientOrder->clientAddress->nama_cp,
                        'nomor_telp' => $detail->clientOrder->clientAddress->nomor_telp,
                        'company' => $detail->clientOrder->clientAddress->client->company ?? 'N/A' // Menambah data company dari client
                    ] : null, // Mengirim data client_address terkait order
                    'client_spec' => $detail->clientOrder->clientSpec ? [
                        'jenis_batubara' => $detail->clientOrder->clientSpec->jenis_batubara,
                        'grade' => $detail->clientOrder->clientSpec->grade,
                        'size' => $detail->clientOrder->clientSpec->size,
                        'kalori' => $detail->clientOrder->clientSpec->kalori,
                        'status' => $detail->clientOrder->clientSpec->status
                    ] : null // Mengirim data client_spec terkait order
                ]
            ];
        });

        // Mengembalikan data dalam format JSON
        return response()->json($formattedOrderDetails, 200);
    }

    /**
     * Menampilkan detail spesifik dari satu order detail berdasarkan ID.
     */
    public function show($id)
{
    // Mengambil data detail order beserta relasi terkait berdasarkan ID detail
    $orderDetail = ClientOrderDetail::with([
        'clientOrder',                     // Mengambil informasi client order terkait
        'clientOrder.clientAddress',       // Mengambil informasi alamat client terkait
        'clientOrder.clientAddress.client', // Mengambil informasi client terkait
        'clientOrder.payments',            // Mengambil semua pembayaran terkait order
        'clientOrder.clientSpec',          // Mengambil spesifikasi client terkait order
        'clientOrder.stock'                // Mengambil informasi stock terkait dengan order
    ])->find($id);

    // Jika detail order tidak ditemukan, kirimkan respon 404
    if (!$orderDetail) {
        return response()->json(['message' => 'Order Detail not found'], 404);
    }

    // Mengambil pembayaran yang terkait dengan detail order ini
    $payment = $orderDetail->clientOrder->payments->where('client_order_detail_id', $id)->first();

    // Mengambil semua pembayaran terkait dengan client_order_id (untuk detail tambahan yang diminta)
    $allPayments = $orderDetail->clientOrder->payments->map(function ($payment) {
        return [
            'id' => $payment->id,
            'termin_ke' => $payment->termin_ke,
            'jumlah_bayar' => $payment->jumlah_bayar,
            'tgl_jatuh_tempo' => $payment->tgl_jatuh_tempo,
            'tgl_bayar' => $payment->tgl_bayar,
            'payment_status' => $payment->payment_status,
            'keterangan' => $payment->keterangan
        ];
    });

    // Mengubah data agar hanya mengirimkan informasi yang diperlukan
    $formattedDetail = [
        'detail_id' => $orderDetail->id,
        'client_order_id' => $orderDetail->client_order_id,
        'no_po' => $orderDetail->no_po,
        'jumlah_order' => $orderDetail->jumlah_order,
        'status' => $orderDetail->status,
        'image' => $orderDetail->image,
        'created_at' => $orderDetail->created_at,
        'updated_at' => $orderDetail->updated_at,
        'payment' => $payment ? [
            'id' => $payment->id,
            'termin_ke' => $payment->termin_ke,
            'jumlah_bayar' => $payment->jumlah_bayar,
            'tgl_jatuh_tempo' => $payment->tgl_jatuh_tempo,
            'tgl_bayar' => $payment->tgl_bayar,
            'payment_status' => $payment->payment_status,
            'keterangan' => $payment->keterangan
        ] : null, // Jika tidak ada pembayaran terkait, set null
        'client_order' => [
            'order_id' => $orderDetail->clientOrder->id,
            'no_po' => $orderDetail->clientOrder->no_po,
            'status_order' => $orderDetail->clientOrder->status_order,
            'order_date' => $orderDetail->clientOrder->order_date,
            'total_order' => $orderDetail->clientOrder->total_order,
            'keterangan' => $orderDetail->clientOrder->keterangan,
            'client_address' => $orderDetail->clientOrder->clientAddress ? [
                'penerima' => $orderDetail->clientOrder->clientAddress->penerima,
                'alamat_lengkap' => $orderDetail->clientOrder->clientAddress->alamat_lengkap,
                'nama_cp' => $orderDetail->clientOrder->clientAddress->nama_cp,
                'nomor_telp' => $orderDetail->clientOrder->clientAddress->nomor_telp,
                'company' => $orderDetail->clientOrder->clientAddress->client->company ?? 'N/A' // Menambah data company dari client
            ] : null, // Mengirim data client_address terkait order
            'client_spec' => $orderDetail->clientOrder->clientSpec ? [
                'jenis_batubara' => $orderDetail->clientOrder->clientSpec->jenis_batubara,
                'grade' => $orderDetail->clientOrder->clientSpec->grade,
                'size' => $orderDetail->clientOrder->clientSpec->size,
                'kalori' => $orderDetail->clientOrder->clientSpec->kalori,
                'status' => $orderDetail->clientOrder->clientSpec->status
            ] : null, // Mengirim data client_spec terkait order
            'stock' => $orderDetail->clientOrder->stock ? [
                'jenis_batubara' => $orderDetail->clientOrder->stock->jenis_batubara,
                'grade' => $orderDetail->clientOrder->stock->grade,
                'size' => $orderDetail->clientOrder->stock->size,
                'kalori' => $orderDetail->clientOrder->stock->kalori,
                'jumlah_stok' => $orderDetail->clientOrder->stock->jumlah_stok,
                'lokasi_simpan' => $orderDetail->clientOrder->stock->lokasi_simpan,
                'harga_per_ton' => $orderDetail->clientOrder->stock->harga_per_ton
            ] : null // Mengirim data stock terkait order
        ],
        // Menambahkan detail pembayaran berdasarkan client_order_id
        'all_payments' => $allPayments
    ];

    return response()->json($formattedDetail, 200);
}


    public function updateStatus(Request $request, $id)
    {
        // Validasi input status dan reason yang diterima
        $request->validate([
            'status' => 'required|string|in:order requested,on process,order delivered,in delivery,arrive at location,rejected,returned,send to another location,send to another client,order completed',
            'reason' => 'nullable|string|max:255', // Reason bersifat nullable dan tidak boleh lebih dari 255 karakter
        ]);

        // Temukan detail order berdasarkan ID
        $detail = ClientOrderDetail::findOrFail($id);

        // Update status dan reason dengan nilai yang diterima dari request
        $detail->status = $request->status;
        $detail->reason = $request->reason; // Menyimpan alasan perubahan status
        $detail->save();

        // Response berhasil
        return response()->json([
            'message' => 'Status updated successfully.',
            'data' => $detail,
        ]);
    }

}
