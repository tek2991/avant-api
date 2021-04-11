<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view profile']);
        Permission::create(['name' => 'edit profile']);


        // create roles and assign created permissions
        $role = Role::create(['name' => 'director']);
        $role->givePermissionTo('view profile');
        $role->givePermissionTo('edit profile');
        
        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo('view profile');

        $user = User::factory()->create([
            'username' => 'director',
            'email' => 'test@example.com',
        ]);

        $user->assignRole('student');
    }
}