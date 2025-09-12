<?php

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::insert([
            [
                'title' => 'Pegawai Teknologi Maklumat',
                'grade' => 'F41',
                'publish_status' => true
            ],
            [
                'title' => 'Penolong Pegawai Teknologi Maklumat Kanan',
                'grade' => 'FA32',
                'publish_status' => true
            ],
            [
                'title' => 'Penolong Pegawai Teknologi Maklumat',
                'grade' => 'FA29',
                'publish_status' => true
            ],
            [
                'title' => 'Juruteknik Komputer',
                'grade' => 'FT22',
                'publish_status' => true
            ],
        ]);
    }
}
