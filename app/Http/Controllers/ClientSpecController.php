<?php

namespace App\Http\Controllers;

use App\Models\ClientSpec;
use App\Models\ClientAddress;
use Illuminate\Http\Request;

class ClientSpecController extends Controller
{
    public function index($client_address_id)
    {
        // Ambil semua Client Specs yang terkait dengan alamat klien tertentu
        $clientAddress = ClientAddress::with('client')->findOrFail($client_address_id);
        $clientSpecs = ClientSpec::where('client_address_id', $client_address_id)->get();
        
        return view('pages.clients.client_spec', compact('clientSpecs', 'clientAddress'));
    }

    public function store(Request $request, $client_address_id)
    {
        // Validasi input
        $request->validate([
            'jenis_batubara' => 'required|string|max:50',
            'grade' => 'required|string|max:50',
            'size' => 'required|string|max:50',
            'kalori' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        // Buat client spec baru
        ClientSpec::create([
            'client_address_id' => $client_address_id,
            'jenis_batubara' => $request->jenis_batubara,
            'grade' => $request->grade,
            'size' => $request->size,
            'kalori' => $request->kalori,
            'status' => $request->status,
        ]);

        return redirect()->route('client.specs.index', $client_address_id)->with('success', 'Client Spec created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jenis_batubara' => 'required|string|max:50',
            'grade' => 'required|string|max:50',
            'size' => 'required|string|max:50',
            'kalori' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        // Ambil client spec berdasarkan ID dan update datanya
        $clientSpec = ClientSpec::findOrFail($id);
        $clientSpec->update($request->all());

        return redirect()->route('client.specs.index', $clientSpec->client_address_id)->with('success', 'Client Spec updated successfully.');
    }

    public function destroy($id)
    {
        // Hapus client spec berdasarkan ID
        $clientSpec = ClientSpec::findOrFail($id);
        $client_address_id = $clientSpec->client_address_id;
        $clientSpec->delete();

        return redirect()->route('client.specs.index', $client_address_id)->with('success', 'Client Spec deleted successfully.');
    }
}
