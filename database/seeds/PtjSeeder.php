<?php

use App\Models\Ptj;
use Illuminate\Database\Seeder;

class PtjSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ptj::insert([
            [
                'name' => 'Bahagian Infostruktur',
                'type' => 'Pentadbiran',
                'publish_status' => true
            ],
            [
                'name' => 'Bahagian Hal Ehwal Akademik & Antarabangsa',
                'type' => 'Pentadbiran',
                'publish_status' => true
            ],
            [
                'name' => 'Fakulti Sains Komputer',
                'type' => 'Akademik',
                'publish_status' => true
            ],
        ]);
    }
}
