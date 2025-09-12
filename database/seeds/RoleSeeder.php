<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'Superadmin',
                'publish_status' => true,
                'guard_name' => 'web',
            ],
            [
                'name' => 'Admin',
                'publish_status' => true,
                'guard_name' => 'web',
            ],
            [
                'name' => 'Pegawai Penyemak',
                'publish_status' => true,
                'guard_name' => 'web',
            ],
            [
                'name' => 'Pemilik',
                'publish_status' => true,
                'guard_name' => 'web',
            ],
        ]);
    }
}
