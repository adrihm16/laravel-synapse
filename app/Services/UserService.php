<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new user with hashed password.
     */
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Update an existing user with hashed password if provided.
     */
    public function updateUser(User $user, array $data): User
    {
        // Only update password if one was provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user;
    }

    /**
     * Delete a user if it's not the current authenticated user.
     */
    public function deleteUser(User $user): bool
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return false;
        }

        return $user->delete();
    }
}
