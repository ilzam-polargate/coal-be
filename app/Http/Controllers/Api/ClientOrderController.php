<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientOrder;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk debugging

class ClientOrderController extends Controller
{
    public function store(Request $request)
    {
        // Logging request data untuk debugging
        Log::info('Request data: ', $request->all());

        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'no_po' => 'required|string|max:50|unique:client_orders,no_po',
            'stock_id' => 'required|exists:stocks,id',
            'client_address_id' => 'required|exists:client_addresses,id',
            'client_spec_id' => 'required|exists:client_specs,id',
            'total_order' => 'required|integer|min:1',
            'total_tagihan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        // Mengambil stock yang dipilih
        $stock = Stock::findOrFail($request->input('stock_id'));

        // Pengecekan jika total_order melebihi jumlah_stok yang ada
        if ($request->input('total_order') > $stock->jumlah_stok) {
            return response()->json([
                'error' => 'Jumlah order tidak boleh melebihi stok yang tersedia.'
            ], 400);
        }

        // Menggunakan DB transaction untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Membuat ClientOrder baru
            $clientOrder = ClientOrder::create([
                'no_po' => $request->input('no_po'),
                'stock_id' => $request->input('stock_id'),
                'client_address_id' => $request->input('client_address_id'),
                'client_spec_id' => $request->input('client_spec_id'),
                'status_order' => $request->input('status_order', 'pending'), // Default: pending
                'order_date' => now(), // Timestamp otomatis
                'total_order' => $request->input('total_order'),
                'total_tagihan' => $request->input('total_tagihan'),
                'keterangan' => $request->input('keterangan'),
            ]);

            // Mengurangi jumlah stok berdasarkan total_order
            $stock->jumlah_stok -= $request->input('total_order');
            $stock->save();

            // Jika semua proses berhasil, commit transaksi
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $clientOrder
            ], 201);

        } catch (\Exception $e) {
            // Jika ada error, rollback transaksi dan log error
            DB::rollBack();
            Log::error('Error processing order: ', ['exception' => $e]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses order. Coba lagi.'
            ], 500);
        }
    }
}
