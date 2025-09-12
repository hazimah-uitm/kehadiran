<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Hazimah',
                'staff_id' => '111111',
                'email' => 'hazimahpethie@gmail.com',
                'password' => Hash::make('user123'),
                'position_id' => 1,
                'ptj_id'        => 1,
                'campus_id'     => 2,
                'phone_no'  => '082111111',
                'publish_status' => true,
                'email_verified_at' => now(),
            ],
        ]);
    }
}
