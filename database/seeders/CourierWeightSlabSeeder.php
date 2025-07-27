<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourierWeightSlab;
use Illuminate\Support\Carbon;

class CourierWeightSlabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weightSlabs = [
            ['weight' => '0.5', 'status' => 1, 'created_at' => Carbon::now()],
            ['weight' => '1', 'status' => 1, 'created_at' => Carbon::now()],
            ['weight' => '2', 'status' => 1, 'created_at' => Carbon::now()],
            ['weight' => '4', 'status' => 1, 'created_at' => Carbon::now()],
            ['weight' => '5', 'status' => 1, 'created_at' => Carbon::now()],
        ];

        CourierWeightSlab::insert($weightSlabs);
    }
}
