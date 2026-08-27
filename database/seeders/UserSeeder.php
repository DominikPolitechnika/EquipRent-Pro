<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed kilku testowych użytkowników (klientów, nie administratorów).
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Anna',
                'surname' => 'Kowalska',
                'email' => 'anna.kowalska@example.com',
                'telephone_number' => '501234567',
                'klub' => 'Fitness Klub Warszawa',
            ],
            [
                'name' => 'Piotr',
                'surname' => 'Nowak',
                'email' => 'piotr.nowak@example.com',
                'telephone_number' => '502345678',
                'klub' => null,
            ],
            [
                'name' => 'Katarzyna',
                'surname' => 'Wiśniewska',
                'email' => 'katarzyna.wisniewska@example.com',
                'telephone_number' => null,
                'klub' => null,
            ],
            [
                'name' => 'Tomasz',
                'surname' => 'Zieliński',
                'email' => 'tomasz.zielinski@example.com',
                'telephone_number' => '504567890',
                'klub' => 'Crossfit Kraków',
                'isBlocked' => true,
            ],
            [
                'name' => 'Magdalena',
                'surname' => 'Lewandowska',
                'email' => 'magdalena.lewandowska@example.com',
                'telephone_number' => '505678901',
                'klub' => null,
            ],
            [
                'name' => 'Marek',
                'surname' => 'Kamiński',
                'email' => 'marek.kaminski@example.com',
                'telephone_number' => '506789012',
                'klub' => 'Silownia Gdańsk',
            ],
            [
                'name' => 'Agnieszka',
                'surname' => 'Wójcik',
                'email' => 'agnieszka.wojcik@example.com',
                'telephone_number' => null,
                'klub' => null,
            ],
            [
                'name' => 'Paweł',
                'surname' => 'Kowalczyk',
                'email' => 'pawel.kowalczyk@example.com',
                'telephone_number' => '507890123',
                'klub' => 'Klub Biegacza Poznań',
            ],
            [
                'name' => 'Ewa',
                'surname' => 'Kaczmarek',
                'email' => 'ewa.kaczmarek@example.com',
                'telephone_number' => '508901234',
                'klub' => null,
                'isBlocked' => true,
            ],
            [
                'name' => 'Michał',
                'surname' => 'Piotrowski',
                'email' => 'michal.piotrowski@example.com',
                'telephone_number' => null,
                'klub' => 'Fitness Klub Warszawa',
            ],
            [
                'name' => 'Marcin',
                'surname' => 'Zając',
                'email' => 'marcin.zajac@example.com',
                'telephone_number' => '509012345',
                'klub' => 'OSiR Wrocław',
            ],
            [
                'name' => 'Justyna',
                'surname' => 'Szymańska',
                'email' => 'justyna.szymanska@example.com',
                'telephone_number' => null,
                'klub' => null,
            ],
            [
                'name' => 'Krzysztof',
                'surname' => 'Dąbrowski',
                'email' => 'krzysztof.dabrowski@example.com',
                'telephone_number' => '510123456',
                'klub' => null,
            ],
            [
                'name' => 'Natalia',
                'surname' => 'Michalska',
                'email' => 'natalia.michalska@example.com',
                'telephone_number' => '511234567',
                'klub' => 'Fitness Klub Warszawa',
            ],
            [
                'name' => 'Bartosz',
                'surname' => 'Woźniak',
                'email' => 'bartosz.wozniak@example.com',
                'telephone_number' => null,
                'klub' => 'Klub Tenisowy Łódź',
            ],
        ];

        foreach ($users as $userData) {
            $isBlocked = $userData['isBlocked'] ?? false;
            unset($userData['isBlocked']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'surname' => $userData['surname'],
                    'password' => Hash::make('password'),
                    'klub' => $userData['klub'],
                ]
            );

            $user->forceFill([
                'telephone_number' => $userData['telephone_number'],
                'isBlocked' => $isBlocked,
            ])->save();
        }
    }
}
