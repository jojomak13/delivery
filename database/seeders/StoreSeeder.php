<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Seller;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::factory()->has(Seller::factory(1, [
            'email' => 'jojo@test.com'
        ]))
        ->has(Branch::factory()->count(5))
        ->create();

        Store::factory()
            ->has(Branch::factory()->count(5))
            ->count(30)->create();
    }
}
