<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    // Menampilkan daftar stok
    public function index()
    {
        $stocks = Stock::all();
        return view('pages.stocks.index', compact('stocks'));
    }

    // Menampilkan form untuk membuat stok baru
    public function create()
    {
        return view('pages.stocks.create');
    }

    // Menyimpan data stok baru
    

    // Menampilkan detail stok
    public function show(Stock $stock)
    {
        return view('stocks.show', compact('stock'));
    }

    // Menampilkan form edit stok
    public function edit(Stock $stock)
    {
        return view('pages.stocks.edit', compact('stock'));
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'foto_item' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'jenis_batubara' => 'required|string|max:250',
    //         'grade' => 'required|string|max:50',
    //         'size' => 'required|string|max:50',
    //         'kalori' => 'required|string|max:50',
    //         'jumlah_stok' => 'required|integer',
    //         'lokasi_simpan' => 'required|string|max:250',
    //         'harga_per_ton' => 'required|string|max:250',
    //         'catatan' => 'nullable|string',
    //         'nama_alias' => 'nullable|string|max:50',
    //         'detail_stock' => 'nullable|string', // Validasi untuk detail_stock
    //     ]);

    //     if ($request->hasFile('foto_item')) {
    //         // Simpan ke folder stocks dan hapus 'public/' dari path yang disimpan ke database
    //         $path = $request->file('foto_item')->store('public/stocks');
    //         $validatedData['foto_item'] = str_replace('public/', '', $path);
    //     }

    //     // Menyimpan stok baru
    //     Stock::create($validatedData);

    //     return redirect()->route('stocks.index')->with('success', 'Stok berhasil ditambahkan.');
    // }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'foto_item' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'jenis_batubara' => 'required|string|max:250',
        'grade' => 'required|string|max:50',
        'size' => 'required|string|max:50',
        'kalori' => 'required|string|max:50',
        'jumlah_stok' => 'required|integer',
        'lokasi_simpan' => 'required|string|max:250',
        'harga_per_ton' => 'required|string|max:250',
        'catatan' => 'nullable|string',
        'nama_alias' => 'nullable|string|max:50',
        'detail_stock' => 'nullable|string', // Validasi untuk detail_stock
    ]);

    if ($request->hasFile('foto_item')) {
        // Simpan ke folder stocks dan hapus 'public/' dari path yang disimpan ke database
        $path = $request->file('foto_item')->store('public/stocks');
        $validatedData['foto_item'] = str_replace('public/', '', $path);
    }

    // Menyimpan stok baru
    Stock::create($validatedData);

    return redirect()->route('stocks.index')->with('success', 'Stok berhasil ditambahkan.');
}

    // Memperbarui data stok
//     public function update(Request $request, Stock $stock)
// {
//     $validatedData = $request->validate([
//         'foto_item' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//         'jenis_batubara' => 'required|string|max:250',
//         'grade' => 'required|string|max:50',
//         'size' => 'required|string|max:50',
//         'kalori' => 'required|string|max:50',
//         'jumlah_stok' => 'required|integer',
//         'lokasi_simpan' => 'required|string|max:250',
//         'harga_per_ton' => 'required|string|max:250',
//         'catatan' => 'nullable|string',
//         'nama_alias' => 'nullable|string|max:50',
//         'detail_stock' => 'nullable|string', // Validasi untuk detail_stock
//     ]);

//     // Hanya perbarui stock_before_update jika jumlah_stok berubah
//     if ($request->input('jumlah_stok') != $stock->jumlah_stok) {
//         // Simpan stok sebelumnya ke stock_before_update
//         $validatedData['stock_before_update'] = $stock->jumlah_stok;
//     } else {
//         // Jika jumlah_stok tidak berubah, hapus dari validasi agar tidak diperbarui
//         unset($validatedData['stock_before_update']);
//     }

//     // Jika ada file foto_item baru, hapus yang lama dan simpan yang baru
//     if ($request->hasFile('foto_item')) {
//         if ($stock->foto_item) {
//             Storage::delete('public/' . $stock->foto_item); // Menghapus file lama
//         }
//         $path = $request->file('foto_item')->store('public/stocks');
//         $validatedData['foto_item'] = str_replace('public/', '', $path); // Simpan path baru tanpa 'public/'
//     }

//     // Update stok dengan data yang sudah divalidasi
//     $stock->update($validatedData);

//     return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui.');
// }

public function update(Request $request, Stock $stock)
{
    $validatedData = $request->validate([
        'foto_item' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'jenis_batubara' => 'required|string|max:250',
        'grade' => 'required|string|max:50',
        'size' => 'required|string|max:50',
        'kalori' => 'required|string|max:50',
        'jumlah_stok' => 'required|integer',
        'lokasi_simpan' => 'required|string|max:250',
        'harga_per_ton' => 'required|string|max:250',
        'catatan' => 'nullable|string',
        'nama_alias' => 'nullable|string|max:50',
        'detail_stock' => 'nullable|string', // Validasi untuk detail_stock
    ]);

    // Jika ada file foto_item baru, hapus yang lama dan simpan yang baru
    if ($request->hasFile('foto_item')) {
        if ($stock->foto_item) {
            Storage::delete('public/' . $stock->foto_item); // Menghapus file lama
        }
        $path = $request->file('foto_item')->store('public/stocks');
        $validatedData['foto_item'] = str_replace('public/', '', $path); // Simpan path baru tanpa 'public/'
    }

    // Update stok dengan data yang sudah divalidasi
    $stock->update($validatedData);

    return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui.');
}


    // Menghapus stok
    public function destroy(Stock $stock)
    {
        if ($stock->foto_item) {
            Storage::delete('public/' . $stock->foto_item); // Hapus foto jika ada
        }

        $stock->delete();  // Ini akan melakukan soft delete
        return redirect()->route('stocks.index')->with('success', 'Stok berhasil dihapus.');
    }
}
