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
        Permission::create(['name' => 'session_crud']);
        Permission::create(['name' => 'session_read']);

        Permission::create(['name' => 'standard_crud']);
        Permission::create(['name' => 'standard_read']);

        Permission::create(['name' => 'section_crud']);
        Permission::create(['name' => 'section_read']);

        Permission::create(['name' => 'teacher_crud']);
        Permission::create(['name' => 'teacher_read']);

        Permission::create(['name' => 'bill_crud']);
        Permission::create(['name' => 'bill_read']);

        Permission::create(['name' => 'manual_payment_crud']);

        Permission::create(['name' => 'student_crud']);
        Permission::create(['name' => 'student_read']);

        Permission::create(['name' => 'appeal_crud']);
        Permission::create(['name' => 'appeal_read']);

        Permission::create(['name' => 'subject_crud']);
        Permission::create(['name' => 'subject_read']);



        // create director roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('system_administration');

        // create director roles and assign permissions
        $role = Role::create(['name' => 'director']);
        $role->givePermissionTo('session_crud');
        $role->givePermissionTo('standard_crud');
        $role->givePermissionTo('section_crud');
        $role->givePermissionTo('teacher_crud');
        $role->givePermissionTo('bill_crud');
        $role->givePermissionTo('manual_payment_crud');
        $role->givePermissionTo('student_crud');
        $role->givePermissionTo('appeal_crud');
        $role->givePermissionTo('subject_crud');

        $role->givePermissionTo('bill_read');
        $role->givePermissionTo('session_read');
        $role->givePermissionTo('standard_read');
        $role->givePermissionTo('section_read');
        $role->givePermissionTo('teacher_read');
        $role->givePermissionTo('student_read');
        $role->givePermissionTo('appeal_read');
        $role->givePermissionTo('subject_read');


        // create teacher roles and assign permissions
        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo('bill_read');
        $role->givePermissionTo('session_read');
        $role->givePermissionTo('standard_read');
        $role->givePermissionTo('section_read');
        $role->givePermissionTo('teacher_read');
        $role->givePermissionTo('student_read');
        $role->givePermissionTo('appeal_read');


        // create student roles and assign permissions
        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo('bill_read');
        $role->givePermissionTo('session_read');
        $role->givePermissionTo('standard_read');
        $role->givePermissionTo('section_read');
        $role->givePermissionTo('teacher_read');
        $role->givePermissionTo('student_read');
        $role->givePermissionTo('appeal_read');



        // create disables roles
        $role = Role::create(['name' => 'disabled']);
    }
}
