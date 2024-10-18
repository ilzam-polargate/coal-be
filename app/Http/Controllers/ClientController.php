<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('pages.clients.index', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:50',
            'nama_purchasing' => 'required|string|max:250',
            'alamat' => 'required|string|max:250',
            'email' => 'required|string|email|max:250',
            'nomor_telep' => 'required|string|max:250',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:50',
            'nama_purchasing' => 'required|string|max:250',
            'alamat' => 'required|string|max:250',
            'email' => 'required|string|email|max:250',
            'nomor_telep' => 'required|string|max:250',
        ]);

        // Jika nama perusahaan berubah, update company_code
        if ($validated['company'] !== $client->company) {
            $validated['company_code'] = Client::generateCompanyCode($validated['company']);
        }

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();  // Soft delete secara otomatis
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    // public function requestDelete(Client $client)
    // {
    //     // Update status deletion requested
    //     $client->update(['deletion_requested' => true]);

    //     return redirect()->route('clients.index')->with('success', 'Deletion request submitted. Awaiting approval.');
    // }

    public function approveDelete(Request $request, Client $client)
    {
        // Jika penghapusan sudah diminta
        if ($client->deletion_requested) {

            Log::info('Approving deletion for client', ['client_id' => $client->id]);

            // Jika konfirmasi penghapusan diterima
            if ($request->has('approve')) {
                // Set deletion_approved menjadi true dan lakukan soft delete
                $client->update([
                    'deletion_approved' => true,
                    'deletion_requested' => false
                ]);

                $client->delete();  // Soft delete atau hapus client

                // Update notification read_at untuk notifikasi yang relevan dengan client ini saja
                $updatedRows = Notification::where('client_id', $client->id)  // Filter dengan client_id
                                ->whereNull('read_at')
                                ->update(['read_at' => now()]);

                Log::info('Updated notifications', ['updatedRows' => $updatedRows]);

                return redirect()->route('clients.index')->with('success', 'Client has been deleted.');
            }

            // Jika penghapusan dibatalkan
            if ($request->has('reject')) {
                // Set deletion_requested menjadi false dan kosongkan alasan penghapusan
                $client->update([
                    'deletion_requested' => false,
                    'deletion_reason' => null
                ]);

                // Update notification read_at untuk notifikasi yang relevan dengan client ini saja
                $updatedRows = Notification::where('client_id', $client->id)  // Filter dengan client_id
                                ->whereNull('read_at')
                                ->update(['read_at' => now()]);

                Log::info('Updated notifications', ['updatedRows' => $updatedRows]);

                return redirect()->route('clients.index')->with('success', 'Deletion request has been cancelled.');
            }
        }

        return redirect()->route('clients.index')->with('error', 'No deletion request found.');
    }
  
    
}
