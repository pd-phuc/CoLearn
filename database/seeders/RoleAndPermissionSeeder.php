<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create-course',
            'edit-course',
            'delete-course',
            'publish-course',
            'manage-users',
            'manage-orders',
            'manage-categories',
            'manage-coupons',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign created permissions
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole = Role::findOrCreate('teacher');
        $teacherRole->givePermissionTo(['create-course', 'edit-course']);

        $studentRole = Role::findOrCreate('student');

        // Create default test accounts if they don't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@colearn.test'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@colearn.test'],
            [
                'name' => 'Teacher User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $teacherUser->assignRole($teacherRole);

        $studentUser = User::firstOrCreate(
            ['email' => 'student@colearn.test'],
            [
                'name' => 'Student User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $studentUser->assignRole($studentRole);
    }
}
