<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
{
    // Create permissions
    $createTicket = Permission::create(['name' => 'create-ticket']);
    $viewTicket = Permission::create(['name' => 'view-ticket']);
    $updateTicket = Permission::create(['name' => 'update-ticket']);
    $manageUsers = Permission::create(['name' => 'manage-users']);
    $closeTicket = Permission::create(['name' => 'close-ticket']);

    // Assign permissions to roles
    $adminRole = Role::findByName('Admin');
    $adminRole->givePermissionTo(['create-ticket', 'view-ticket', 'update-ticket', 'manage-users', 'close-ticket']);

    $supportAgentRole = Role::findByName('Support Agent');
    $supportAgentRole->givePermissionTo(['view-ticket', 'update-ticket', 'close-ticket']);

    $customerRole = Role::findByName('Customer');
    $customerRole->givePermissionTo(['create-ticket', 'view-ticket']);
}
}
