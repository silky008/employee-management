<?php
namespace App\services;

use App\Jobs\LogAuditJob;
use App\Models\Client;

class ClientService
{
    public function update(Client $client, array $data): Client
    {
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('clients', 'public');
        }

        $client->update($data);

        return $client;
    }
    public function delete(Client $client): void
    {
        $snapshot = $client->toArray();

        $client->delete();

        $this->audit('deleted', $client, $snapshot);
    }

    private function audit(string $action, Client $client, $changes = null): void
    {
        LogAuditJob::dispatch([
            'actor_id'    => auth()->id(),
            'action'      => $action,
            'target_type' => 'Client',
            'target_id'   => $client->id,
            'changes'     => $changes,
        ]);
    }
}
