<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;

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
}
