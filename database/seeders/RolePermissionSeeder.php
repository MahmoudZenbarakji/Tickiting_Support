<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الأدوار
        $admin = Role::create(['name' => 'Admin']);
        $supportAgent = Role::create(['name' => 'Support Agent']);
        $customer = Role::create(['name' => 'Customer']);

        // إنشاء الصلاحيات
        Permission::create(['name' => 'create tickets']);
        Permission::create(['name' => 'view all tickets']);
        Permission::create(['name' => 'reply to tickets']);
        Permission::create(['name' => 'close tickets']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view statistics']);

        // تعيين الصلاحيات للأدوار
        $customer->givePermissionTo(['create tickets', 'reply to tickets']);
        $supportAgent->givePermissionTo(['view all tickets', 'reply to tickets', 'close tickets']);
        $admin->givePermissionTo(Permission::all());
    }
}
