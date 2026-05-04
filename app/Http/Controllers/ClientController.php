<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Show all clients
    public function index()
    {
        $clients = Client::where('user_id', auth()->id())
                         ->latest()
                         ->paginate(10);
        return view('clients.index', compact('clients'));
    }

    // Show create form
    public function create()
    {
        return view('clients.create');
    }

    // Save new client
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,NULL,id,user_id,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Client::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')
                         ->with('success', 'Client created successfully!');
    }

    // Show single client
    public function show(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);
        $client->load('invoices');
        return view('clients.show', compact('client'));
    }

    // Show edit form
    public function edit(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);
        return view('clients.edit', compact('client'));
    }

    // Update client
    public function update(Request $request, Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $client->id . ',id,user_id,' . auth()->id(),
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $client->update($request->only('name', 'email', 'phone', 'address'));

        return redirect()->route('clients.index')
                         ->with('success', 'Client updated successfully!');
    }

    // Delete client
    public function destroy(Client $client)
    {
        abort_if($client->user_id !== auth()->id(), 403);
        $client->delete();
        return redirect()->route('clients.index')
                         ->with('success', 'Client deleted successfully!');
    }
}