<?php
namespace App\services;

use App\Jobs\LogAuditJob;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function list(array $filters)
    {
        $query = User::with('role');
        // 🔹 Search by name or email
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // 🔹 Filter by role
        if (! empty($filters['role'])) {
            $query->whereHas('role', fn($q) =>
                $q->where('name', $filters['role'])
            );

        }

        $query->orderBy(
            $filters['sort_field'] ?? 'name',
            $filters['sort_direction'] ?? 'asc'
        );
        // 🔹 Pagination (10 per page)
        return $query->paginate(10);
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => $data['role_id'],
        ]);
        $this->audit('created', $user);
        return $user->load('role');

    }
    public function update(User $user, array $data): User
    {
        $old = $user->getOriginal();

        $user->update($data);

        $this->audit('updated', $user, $old);

        return $user->load('role');
    }

    public function delete(User $user): void
    {
        // Prevent deleting yourself

        if (auth()->id() === $user->id) {
            abort(403, 'You cannot delete your own account');
        }

        $snapshot = $user->toArray();
        $user->delete();

        $this->audit('deleted', $user, $snapshot);
    }

    private function audit(string $action, User $user, $changes = null): void
    {
        LogAuditJob::dispatch([
            'actor_id'    => auth()->id(),
            'action'      => $action,
            'target_type' => 'User',
            'target_id'   => $user->id,
            'changes'     => $changes,
        ]);
    }

}
