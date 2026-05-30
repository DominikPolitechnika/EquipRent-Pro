<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Role wymagane przy rejestracji (users.role → roles.id).
     */
    public function run(): void
    {
        DB::table('roles')->upsert([
            ['id' => 0, 'roleName' => 'Undefined'],
            ['id' => 1, 'roleName' => 'Zawodnik'],
            ['id' => 2, 'roleName' => 'Administrator'],
        ], ['id'], ['roleName']);
    }
}
