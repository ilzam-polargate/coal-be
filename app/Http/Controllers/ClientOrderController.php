<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\Stock;
use App\Models\ClientSpec;
use App\Models\ClientAddress;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    public function index()
    {
        // Mengambil data client orders dengan relasi stock, client address, client spec, dan total jumlah order
        $orders = ClientOrder::with(['stock', 'clientAddress', 'clientAddress.specs'])
                            ->withSum('details', 'jumlah_order') // Tambahkan total jumlah_order dari relasi details
                            ->get();

        $stocks = Stock::all(); // Data untuk select stock
        $addresses = ClientAddress::all(); // Data untuk select client address
        $clientSpecs = ClientSpec::all(); // Data untuk select client specs

        return view('pages.clients.client_orders', compact('orders', 'stocks', 'addresses', 'clientSpecs'));
    }


    public function store(Request $request)
{
    $request->validate([
        'no_po' => 'required|string|max:50|unique:client_orders,no_po',
        'stock_id' => 'required|exists:stocks,id',
        'client_address_id' => 'required|exists:client_addresses,id',
        'client_spec_id' => 'required|exists:client_specs,id',
        'total_order' => 'required|integer',
        'keterangan' => 'nullable|string',
    ]);

    $stock = Stock::findOrFail($request->stock_id);

    // Cek apakah jumlah order melebihi stok yang tersedia
    if ($request->total_order > $stock->jumlah_stok) {
        return response()->json(['errors' => ['total_order' => 'Jumlah order tidak boleh melebihi jumlah stok yang tersedia.']], 422);
    }

    // Simpan stok sebelum diperbarui ke stock_before_update
    $stock->update(['stock_before_update' => $stock->jumlah_stok]);

    // Buat order baru
    ClientOrder::create([
        'no_po' => $request->no_po,
        'stock_id' => $request->stock_id,
        'client_address_id' => $request->client_address_id,
        'client_spec_id' => $request->client_spec_id,
        'status_order' => 'order requested',
        'order_date' => now(),
        'total_order' => $request->total_order,
        'keterangan' => $request->keterangan,
    ]);

    // Kurangi jumlah stok setelah order dibuat
    $stock->decrement('jumlah_stok', $request->total_order);

    return response()->json(['success' => true]);
}


public function update(Request $request, ClientOrder $clientOrder)
{
    $request->validate([
        'no_po' => 'required|string|max:50|unique:client_orders,no_po,' . $clientOrder->id,
        'stock_id' => 'required|exists:stocks,id',
        'client_address_id' => 'required|exists:client_addresses,id',
        'client_spec_id' => 'required|exists:client_specs,id',
        'total_order' => 'required|integer',
        'keterangan' => 'nullable|string',
    ]);

    $stock = Stock::findOrFail($request->stock_id);

    // Kembalikan stok lama (sebelum update)
    $stock->increment('jumlah_stok', $clientOrder->total_order);

    // Cek apakah jumlah order baru melebihi stok yang tersedia
    if ($request->total_order > $stock->jumlah_stok) {
        return response()->json(['errors' => ['total_order' => 'Jumlah order tidak boleh melebihi jumlah stok yang tersedia.']], 422);
    }

    // Simpan stok sebelum diperbarui ke stock_before_update
    $stock->update(['stock_before_update' => $stock->jumlah_stok]);

    // Update order
    $clientOrder->update([
        'no_po' => $request->no_po,
        'stock_id' => $request->stock_id,
        'client_address_id' => $request->client_address_id,
        'client_spec_id' => $request->client_spec_id,
        'total_order' => $request->total_order,
        'keterangan' => $request->keterangan,
    ]);

    // Kurangi jumlah stok setelah update order
    $stock->decrement('jumlah_stok', $request->total_order);

    return response()->json(['success' => true]);
}

    public function destroy(ClientOrder $clientOrder)
    {
        // Hapus order
        $clientOrder->delete();
        return redirect()->route('client_orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        // Validasi status order
        $request->validate([
            'id' => 'required|exists:client_orders,id',
            'status_order' => 'required|string',
        ]);

        // Cari order berdasarkan ID dan update statusnya
        $clientOrder = ClientOrder::find($request->id);
        $clientOrder->update([
            'status_order' => $request->status_order
        ]);

        return response()->json(['success' => true]);
    }

    public function getClientSpecsByAddress($client_address_id)
    {
        $specs = ClientSpec::where('client_address_id', $client_address_id)->get();
        return response()->json($specs);
    }


}
