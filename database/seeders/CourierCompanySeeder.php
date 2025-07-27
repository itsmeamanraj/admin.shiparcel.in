<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourierCompany;
use Carbon\Carbon;

class CourierCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            ['name' => 'Delhivery', 'status' => 1, 'created_at' => Carbon::now()],
            ['name' => 'Xpressbeez', 'status' => 1, 'created_at' => Carbon::now()],
        ];

        CourierCompany::insert($companies);
    }
}
