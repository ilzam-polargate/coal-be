<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\ClientPayment;
use App\Models\ClientOrderDetail;
use Illuminate\Http\Request;

class ClientPaymentController extends Controller
{
    // Tampilkan daftar pembayaran untuk order tertentu
    public function index($orderId)
    {
        $order = ClientOrder::findOrFail($orderId);
        $payments = ClientPayment::where('client_order_id', $orderId)->get();
        // Ambil detail order yang belum terkait dengan pembayaran apa pun
        // Ambil detail order yang belum terkait dengan pembayaran apa pun
$availableOrderDetails = ClientOrderDetail::where('client_order_id', $orderId)
->whereDoesntHave('clientPayments') // Hanya ambil yang belum memiliki payment
->get();


        return view('pages.clients.client_payment', compact('order', 'payments', 'availableOrderDetails'));
    }

    // Simpan pembayaran baru
    public function store(Request $request, $orderId)
    {
        // Hitung termin ke otomatis berdasarkan pembayaran sebelumnya
        $lastPayment = ClientPayment::where('client_order_id', $orderId)->orderBy('termin_ke', 'desc')->first();
        $nextTermin = $lastPayment ? $lastPayment->termin_ke + 1 : 1; // Jika tidak ada pembayaran, mulai dari 1

        // Lakukan validasi input
        $request->validate([
            'jumlah_bayar' => 'required|numeric',
            'tgl_jatuh_tempo' => 'required|date',
            'client_order_detail_id' => 'required|exists:client_order_details,id', // Validasi untuk detail order
        ]);

        // Buat pembayaran baru
        ClientPayment::create([
            'client_order_id' => $orderId,
            'client_order_detail_id' => $request->client_order_detail_id, // Set detail order ID
            'termin_ke' => $nextTermin, // Set termin ke otomatis
            'jumlah_bayar' => $request->jumlah_bayar,
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'tgl_bayar' => $request->tgl_bayar,
            'payment_status' => 'unpaid', // Status default
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('client_payments.index', $orderId)->with('success', 'Payment added successfully.');
    }

    // Edit pembayaran
    public function update(Request $request, $orderId, $paymentId)
    {
        $payment = ClientPayment::findOrFail($paymentId);

        // Validasi input
        $request->validate([
            'termin_ke' => 'required|integer',
            'jumlah_bayar' => 'required|numeric',
            'tgl_jatuh_tempo' => 'required|date',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        // Jika status diubah dari 'unpaid' menjadi 'paid', set tgl_bayar ke waktu sekarang
        if ($request->payment_status == 'paid' && $payment->payment_status == 'unpaid') {
            $payment->update([
                'termin_ke' => $request->termin_ke,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                'payment_status' => $request->payment_status,
                'tgl_bayar' => now(), // Set tgl_bayar ke waktu sekarang
                'keterangan' => $request->keterangan,
            ]);
        }
        // Jika status diubah dari 'paid' menjadi 'unpaid', hapus tgl_bayar
        else if ($request->payment_status == 'unpaid' && $payment->payment_status == 'paid') {
            $payment->update([
                'termin_ke' => $request->termin_ke,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                'payment_status' => $request->payment_status,
                'tgl_bayar' => null, // Hapus tgl_bayar
                'keterangan' => $request->keterangan,
            ]);
        }
        // Jika status tetap sama atau tidak ada perubahan dari paid/unpaid, update tanpa mengubah tgl_bayar
        else {
            $payment->update($request->all());
        }

        return redirect()->route('client_payments.index', $orderId)->with('success', 'Payment updated successfully.');
    }



    // Hapus pembayaran
    public function destroy($orderId, $paymentId)
    {
        $payment = ClientPayment::findOrFail($paymentId);
        $payment->delete();

        return redirect()->route('client_payments.index', $orderId)->with('success', 'Payment deleted successfully.');
    }

    public function updateStatus(Request $request, $paymentId)
    {
        // Validasi status pembayaran
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        // Cari data pembayaran
        $payment = ClientPayment::findOrFail($paymentId);

        // Jika status diubah menjadi 'paid' dan sebelumnya 'unpaid', set tgl_bayar ke waktu sekarang
        if ($request->payment_status == 'paid' && $payment->payment_status == 'unpaid') {
            $payment->update([
                'payment_status' => $request->payment_status,
                'tgl_bayar' => now(), // Set tgl_bayar ke waktu sekarang
            ]);
        }
        // Jika status diubah dari 'paid' menjadi 'unpaid', hapus tgl_bayar (buat null)
        else if ($request->payment_status == 'unpaid' && $payment->payment_status == 'paid') {
            $payment->update([
                'payment_status' => $request->payment_status,
                'tgl_bayar' => null, // Hapus tgl_bayar
            ]);
        }
        // Jika status tetap sama, update tanpa mengubah tgl_bayar
        else {
            $payment->update([
                'payment_status' => $request->payment_status,
            ]);
        }

        // Berikan respon sukses dalam format JSON
        return response()->json(['message' => 'Payment status updated successfully.']);
    }



}
