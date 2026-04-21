<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\services\ClientService;

class ClientController extends Controller
{
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $client = Client::create($data);

        return response()->json([
            'message' => 'Client created successfully',
            'data'    => $client,
        ]);
    }

    public function index()
    {
        return Client::latest()->paginate(10);
    }

    public function update(UpdateClientRequest $request, Client $client, ClientService $service)
    {
        $client = $service->update($client, $request->validated());

        return response()->json([
            'message' => 'Client updated successfully',
            'data'    => $client,
        ]);
    }

    public function destroy(Client $client, ClientService $service)
    {
        $service->delete($client);

        return response()->json([
            'message' => 'Client deleted successfully',
        ]);
    }

    public function show(Client $client)
    {
        return response()->json($client);
    }

}
