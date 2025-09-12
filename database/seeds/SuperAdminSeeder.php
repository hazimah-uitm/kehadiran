<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);

        $superadmin = User::create([
            'email' => 'hazimahpte@gmail.com',
            'name' => 'Super Admin',
            'staff_id' => '100001',
            'password' => Hash::make('superadmin123'),
            'position_id' => 1,
            'ptj_id'        => 1,  
            'campus_id' => 2,
            'phone_no' => '082000000',
            'publish_status' => true,
            'email_verified_at' => now(), 
        ]);

        $superadmin->assignRole($superadminRole);

        $this->assignPermissionsToSuperAdmin();
    }

    /**
     * Assign all permissions to the Super Admin role.
     */
    protected function assignPermissionsToSuperAdmin()
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->syncPermissions(Permission::all());
    }
}
