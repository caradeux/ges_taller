<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@gestaller.cl'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
                'active'   => true,
            ]
        );

        $this->call(SampleDataSeeder::class);
    }
}
