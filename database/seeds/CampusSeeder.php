<?php

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campus::insert([
            [
                'name' => 'Samarahan',
                'publish_status' => true
            ],
            [
                'name' => 'Samarahan 2',
                'publish_status' => true
            ],
            [
                'name' => 'Mukah',
                'publish_status' => true
            ],
        ]);
    }
}
