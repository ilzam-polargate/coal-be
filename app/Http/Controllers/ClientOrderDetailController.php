<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\ClientOrderDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ClientOrderDetailController extends Controller
{
    // Menampilkan halaman manage order details
    public function index($orderId)
    {
        // Ambil data client_order dengan client_addresses dan client_specs
        $order = ClientOrder::with(['details', 'clientAddress.specs'])->findOrFail($orderId);

        return view('pages.clients.client_order_details', compact('order'));
    }


    // Menyimpan order detail baru
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'jumlah_order' => 'required|integer',
            'status' => 'string|max:50|in:order requested,on process,order delivered,in delivery,arrive at location,rejected,returned,send to another location,send to another client,order completed', // Validasi untuk status sebagai string
            'image' => 'nullable|image|max:2048' // Validasi file image
        ]);

        // Ambil data order terkait beserta relasi client
        $order = ClientOrder::with('clientAddress.client')->findOrFail($orderId);

        // Cek apakah client ada
        if (!$order->clientAddress || !$order->clientAddress->client) {
            return redirect()->route('client_order_details.index', $orderId)
                             ->withErrors('Client terkait tidak ditemukan.');
        }

        // Generate nomor PO berdasarkan aturan tertentu
        $date = now()->format('Ymd'); // Tanggal dalam format YYYYMMDD
        $companyCode = $order->clientAddress->client->company_code; // Menggunakan company_code dari client
        $orderDetailCount = ClientOrderDetail::where('client_order_id', $orderId)->count() + 1; // Nomor urut detail per order

        $no_po = $companyCode . '-' . $date . '-' . str_pad($orderDetailCount, 3, '0', STR_PAD_LEFT); // Contoh: ABC-20230928-001

        // Meng-handle upload gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('order_images', 'public');
        }

        ClientOrderDetail::create([
            'client_order_id' => $orderId,
            'no_po' => $no_po, // Gunakan nomor PO yang di-generate
            'jumlah_order' => $request->jumlah_order,
            'status' => $request->status ?? 'order requested', // Default status jika tidak diisi
            'image' => $imagePath, // Simpan path image
        ]);

        return redirect()->route('client_order_details.index', $orderId)
                         ->with('success', 'Order detail berhasil ditambahkan.');
    }

    // Mengupdate order detail
    public function update(Request $request, $orderId, $detailId)
    {
        $request->validate([
            'jumlah_order' => 'required|integer',
            // 'status' dihapus dari validasi karena sudah ada fungsi khusus update status
            'image' => 'nullable|image|max:2048'
        ]);

        $detail = ClientOrderDetail::where('id', $detailId)
                                    ->where('client_order_id', $orderId)
                                    ->firstOrFail();

        $imagePath = $detail->image; // Menyimpan image path lama
        if ($request->hasFile('image')) {
            if ($detail->image) {
                Storage::disk('public')->delete($detail->image); // Hapus gambar lama
            }
            $imagePath = $request->file('image')->store('order_images', 'public');
        }

        $detail->update([
            // 'no_po' tidak diupdate
            'jumlah_order' => $request->jumlah_order,
            'image' => $imagePath, // Update image path
        ]);

        return redirect()->route('client_order_details.index', $orderId)
                        ->with('success', 'Order detail berhasil diupdate.');
    }


    // Menghapus order detail
    public function destroy($orderId, $detailId)
    {
        $detail = ClientOrderDetail::where('id', $detailId)
            ->where('client_order_id', $orderId)
            ->firstOrFail();

        // Hapus gambar jika ada
        if ($detail->image) {
            Storage::disk('public')->delete($detail->image);
        }

        $detail->delete();

        return redirect()->route('client_order_details.index', $orderId)
            ->with('success', 'Order detail berhasil dihapus.');
    }

    public function updateStatus(Request $request)
    {
        Log::info('updateStatus method called', ['request' => $request->all()]);

        try {
            $validated = $request->validate([
                'id' => 'required|exists:client_order_details,id',
                'status' => 'required|string|max:50|in:order requested,on process,order delivered,in delivery,arrive at location,rejected,returned,send to another location,send to another client,order completed',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            $orderDetail = ClientOrderDetail::findOrFail($request->id);
            $orderDetail->status = $request->status;
            $orderDetail->save();

            Log::info('Status updated successfully', ['orderDetail' => $orderDetail]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in updateStatus', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in updateStatus', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while updating status'], 500);
        }
    }

}
