<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::factory()->create(['name' => 'Pizza']);
        Type::factory()->create(['name' => 'Fried Chicken']);
        Type::factory()->create(['name' => 'IceCream']);
        Type::factory()->create(['name' => 'Grills']);
        Type::factory()->create(['name' => 'Fish']);
    }
}
