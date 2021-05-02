<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'system_administration']);
        Permission::create(['name' => 'view profile']);
        Permission::create(['name' => 'edit profile']);
        Permission::create(['name' => 'session CRUD']);
        Permission::create(['name' => 'standard CRUD']);
        Permission::create(['name' => 'section CRUD']);


        // create director roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('system_administration');

        // create director roles and assign permissions
        $role = Role::create(['name' => 'director']);
        $role->givePermissionTo('view profile');
        $role->givePermissionTo('edit profile');
        $role->givePermissionTo('session CRUD');
        $role->givePermissionTo('standard CRUD');
        $role->givePermissionTo('section CRUD');
        
        // create teacher roles and assign permissions
        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo('view profile');
        $role->givePermissionTo('edit profile');

        // create student roles and assign permissions
        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo('view profile');
        $role->givePermissionTo('edit profile');
    }
}