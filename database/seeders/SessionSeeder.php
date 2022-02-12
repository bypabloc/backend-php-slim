<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Session;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::factory()
                ->count(49)
                ->create();
    }
}
