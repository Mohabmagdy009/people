<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
            'id' => 1,
                'name' => 'role-view',
                'display_name' => 'Display Role',
                'description' => 'See Role',
            ],
            [
            'id' => 2,
                'name' => 'role-create',
                'display_name' => 'Create Role',
                'description' => 'Create New Role',
            ],
            [
            'id' => 3,
                'name' => 'role-edit',
                'display_name' => 'Edit Role',
                'description' => 'Edit Role',
            ],
            [
            'id' => 4,
                'name' => 'role-delete',
                'display_name' => 'Delete Role',
                'description' => 'Delete Role',
            ],
            [
            'id' => 5,
                'name' => 'role-assign',
                'display_name' => 'Assign Role',
                'description' => 'Assign Role',
            ],
            [
            'id' => 6,
                'name' => 'user-view',
                'display_name' => 'Display User',
                'description' => 'See User',
            ],
            [
            'id' => 7,
                'name' => 'user-create',
                'display_name' => 'Create User',
                'description' => 'Create New User',
            ],
            [
            'id' => 8,
                'name' => 'user-edit',
                'display_name' => 'Edit User',
                'description' => 'Edit User',
            ],
            [
            'id' => 9,
                'name' => 'user-delete',
                'display_name' => 'Delete User',
                'description' => 'Delete User',
            ],
          [
            'id' => 10,
                'name' => 'user-view-all',
                'display_name' => 'View All User',
                'description' => 'View All User',
            ],
            [
            'id' => 11,
                'name' => 'activity-view',
                'display_name' => 'Display Activity',
                'description' => 'See Activity',
            ],
            [
            'id' => 12,
                'name' => 'activity-create',
                'display_name' => 'Create Activity',
                'description' => 'Create New Activity',
            ],
            [
            'id' => 13,
                'name' => 'activity-edit',
                'display_name' => 'Edit Activity',
                'description' => 'Edit Activity',
            ],
            [
            'id' => 14,
                'name' => 'activity-delete',
                'display_name' => 'Delete Activity',
                'description' => 'Delete Activity',
            ],
            [
            'id' => 15,
                'name' => 'project-view',
                'display_name' => 'Display Project',
                'description' => 'See Project',
            ],
            [
            'id' => 16,
                'name' => 'project-create',
                'display_name' => 'Create Project',
                'description' => 'Create New Project',
            ],
            [
            'id' => 17,
                'name' => 'project-edit',
                'display_name' => 'Edit Project',
                'description' => 'Edit Project',
            ],
            [
            'id' => 18,
                'name' => 'project-delete',
                'display_name' => 'Delete Project',
                'description' => 'Delete Project',
            ],
            [
            'id' => 19,
                'name' => 'otl-upload',
                'display_name' => 'Upload OTL',
                'description' => 'Upload xls file with OTL data',
            ],
            [
            'id' => 20,
                'name' => 'tools-activity-view',
                'display_name' => 'Tools Activity View',
                'description' => 'Tools Activity View',
            ],
          [
            'id' => 21,
                'name' => 'tools-activity-new',
                'display_name' => 'Tools Activity New',
                'description' => 'Tools Activity New',
            ],
          [
            'id' => 22,
                'name' => 'tools-activity-edit',
                'display_name' => 'Tools Activity Edit',
                'description' => 'Tools Activity Edit',
            ],
          [
            'id' => 23,
                'name' => 'tools-activity-all-view',
                'display_name' => 'Tools Activity All View',
                'description' => 'Tools Activity All View',
            ],
          [
            'id' => 24,
                'name' => 'tools-activity-all-edit',
                'display_name' => 'Tools Activity All Edit',
                'description' => 'Tools Activity All Edit',
            ],
            [
            'id' => 25,
                'name' => 'dashboard-view',
                'display_name' => 'Dashboard View',
                'description' => 'Dashboard View',
            ],
            [
            'id' => 26,
                'name' => 'dashboard-all-view',
                'display_name' => 'Dashboard View All',
                'description' => 'Dashboard view for all users and managers',
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}
