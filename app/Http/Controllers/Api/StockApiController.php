<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockApiController extends Controller
{
    // Menampilkan semua data stok
    public function index()
    {
        $stocks = Stock::all(); // Mengambil semua data dari tabel stocks
        return response()->json($stocks, 200); // Mengembalikan response dalam format JSON
    }

    // Menampilkan detail stok berdasarkan ID
    public function show($id)
    {
        $stock = Stock::find($id);

        if (!$stock) {
            return response()->json(['message' => 'Stock not found'], 404); // Jika data tidak ditemukan
        }

        return response()->json($stock, 200); // Mengembalikan response dalam format JSON
    }
}
