<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Pengurusan Pengguna' => [
                ['name' => 'Tambah Pengguna', 'category' => 'Pengurusan Pengguna'],
                ['name' => 'Edit Pengguna', 'category' => 'Pengurusan Pengguna'],
                ['name' => 'Padam Pengguna', 'category' => 'Pengurusan Pengguna'],
                ['name' => 'Lihat Pengguna', 'category' => 'Pengurusan Pengguna'],
            ],
            'Pengurusan Kampus' => [
                ['name' => 'Tambah Kampus', 'category' => 'Pengurusan Kampus'],
                ['name' => 'Edit Kampus', 'category' => 'Pengurusan Kampus'],
                ['name' => 'Padam Kampus', 'category' => 'Pengurusan Kampus'],
                ['name' => 'Lihat Kampus', 'category' => 'Pengurusan Kampus'],
            ],
            'Pengurusan Jawatan' => [
                ['name' => 'Tambah Jawatan', 'category' => 'Pengurusan Jawatan'],
                ['name' => 'Edit Jawatan', 'category' => 'Pengurusan Jawatan'],
                ['name' => 'Padam Jawatan', 'category' => 'Pengurusan Jawatan'],
                ['name' => 'Lihat Jawatan', 'category' => 'Pengurusan Jawatan'],
            ],
        ];

        foreach ($permissions as $category => $permissionArray) {
            foreach ($permissionArray as $permissionData) {
                Permission::firstOrCreate([
                    'name' => $permissionData['name'],
                    'category' => $permissionData['category'],
                    'guard_name' => 'web',
                ]);
            }
        }

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
