<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access'
        ]);

        // Create print manager role
        $printManagerRole = Role::create([
            'name' => 'print_manager',
            'description' => 'Manages print orders and fulfillment'
        ]);

        // Create permissions
        $permissions = [
            // Admin panel access
            'access_admin_panel' => 'Access the admin control panel',

            // Print order management
            'view_print_orders' => 'View all print orders',
            'manage_print_orders' => 'Manage print order status and details',
            'export_print_orders' => 'Export print orders data',
            'delete_print_orders' => 'Delete print orders',

            // Print settings
            'manage_print_settings' => 'Manage print sizes and pricing',
            'view_print_reports' => 'View print order reports and analytics',

            // User management
            'manage_users' => 'Manage user accounts',
            'manage_roles' => 'Manage roles and permissions',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create([
                'name' => $name,
                'description' => $description
            ]);
        }

        // Assign all permissions to admin role
        $adminRole->syncPermissions(array_keys($permissions));

        // Assign specific permissions to print manager role
        $printManagerRole->syncPermissions([
            'access_admin_panel',
            'view_print_orders',
            'manage_print_orders',
            'export_print_orders',
            'view_print_reports'
        ]);

        // Find or create admin user
        $adminEmail = config('admin.email', 'admin@example.com');
        $adminUser = User::where('email', $adminEmail)->first();

        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Create print order policies
        $printOrderPermissions = [
            'view_print_orders',
            'manage_print_orders',
            'export_print_orders',
            'delete_print_orders'
        ];

        foreach ($printOrderPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'description' => ucfirst(str_replace('_', ' ', $permission))
                ]);
            }
        }
    }
}
