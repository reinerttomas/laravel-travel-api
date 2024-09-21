<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataTransferObjects\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final readonly class CreateUserAction
{
    public function execute(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
            ]);
            $user->roles()->attach($data->role->id);

            return $user;
        });
    }
}
