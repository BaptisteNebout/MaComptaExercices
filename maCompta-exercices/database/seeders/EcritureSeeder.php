<?php

namespace Database\Seeders;

use App\Models\Ecriture;
use Illuminate\Database\Seeder;

class EcritureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ecriture::factory()->count(50)->create();
    }
}
