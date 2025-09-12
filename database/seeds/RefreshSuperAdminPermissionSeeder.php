<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RefreshSuperAdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->syncPermissions(Permission::all());
    }
}
