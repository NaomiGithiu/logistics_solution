<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        
        $permissions = [
            'create trips',
            'view trips',
            'assign trips',
            'update trips',
            'cancel trips',
            'delete trips',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'view drivers',
            'verify drivers',
            'assign trips to drivers',
            'view customers',
            'ban customers',
            'delete customers',
            'add vehicles',
            'edit vehicles',
            'delete vehicles',
            'view vehicles',
            'view payments',
            'issue refunds',
            'view invoices',
            'manage settings',
            'view dashboard',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
 
}