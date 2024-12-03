<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Input\RegistrationInput;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(RegistrationInput $registrationInput): void
    {
        /** @var User $user */
        User::create([
            'name' => $registrationInput->name,
            'email' => $registrationInput->email,
            'country' => $registrationInput->country,
            'language' => $registrationInput->language,
            'password' => Hash::make($registrationInput->password),
        ]);
    }
}
