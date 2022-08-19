<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                  // Reset cached roles and permissions
                  app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                  // create permissions
                  Permission::create(['name' => 'All']);
                  Permission::create(['name' => 'Create']);
                  Permission::create(['name' => 'Delete']);
                  Permission::create(['name' => 'Update']);
                  Permission::create(['name' => 'View']);
          
                  // create roles and assign created permissions
          
                  // or may be done by chaining
                  $role = Role::create(['name' => 'doctor'])
                  ->givePermissionTo(['View']);
  
                  $role = Role::create(['name' => 'lab'])
                  ->givePermissionTo(['View']);
  
                  $role = Role::create(['name' => 'wholesaler'])
                  ->givePermissionTo(['View']);

                  $role = Role::create(['name' => 'user'])
                  ->givePermissionTo(['View']);
          
                  $role = Role::create(['name' => 'pharmacy'])
                  ->givePermissionTo(['View']);

                  $role = Role::create(['name' => 'super-admin']);
                  $role->givePermissionTo(Permission::all());
    }
}
