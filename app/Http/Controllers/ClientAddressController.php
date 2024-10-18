<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAddress;
use App\Models\ClientSpec;
use Illuminate\Http\Request;

class ClientAddressController extends Controller
{
    public function index($clientId)
    {
        // Temukan client berdasarkan ID
        $client = Client::findOrFail($clientId);
        
        // Ambil client address bersama dengan spesifikasinya (relasi 'specs')
        $addresses = ClientAddress::with('specs')->where('client_id', $clientId)->get();

        return view('pages.clients.client_addresses', compact('client', 'addresses'));
    }

    public function store(Request $request, Client $client)
    {
        // Validasi data
        $request->validate([
            'penerima' => 'required|string|max:250',
            'alamat_lengkap' => 'required|string|max:250',
            'nama_cp' => 'required|string|max:250',
            'nomor_telp' => 'required|string|max:250',
        ]);

        // Simpan address
        ClientAddress::create([
            'client_id' => $client->id,
            'penerima' => $request->penerima,
            'alamat_lengkap' => $request->alamat_lengkap,
            'nama_cp' => $request->nama_cp,
            'nomor_telp' => $request->nomor_telp,
        ]);

        return redirect()->route('clients.addresses', $client->id)->with('success', 'Address created successfully.');
    }

    public function update(Request $request, Client $client, ClientAddress $clientAddress)
    {
        // Validasi data
        $request->validate([
            'penerima' => 'required|string|max:250',
            'alamat_lengkap' => 'required|string|max:250',
            'nama_cp' => 'required|string|max:250',
            'nomor_telp' => 'required|string|max:250',
        ]);

        // Update address
        $clientAddress->update($request->all());

        return redirect()->route('clients.addresses', $client->id)->with('success', 'Address updated successfully.');
    }

    public function destroy(Client $client, ClientAddress $clientAddress)
    {
        // Hapus address
        $clientAddress->delete();

        return redirect()->route('clients.addresses', $client->id)->with('success', 'Address deleted successfully.');
    }
}
