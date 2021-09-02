<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        $createPostsPermission = Permission::create(['name' => 'create-posts']);

        $admin = new User();
        $admin->name = 'John Doe';
        $admin->email = 'john@doe.com';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->assignRole($adminRole);
        $admin->givePermissionTo($createPostsPermission);

        $user = new User();
        $user->name = 'Mike Thomas';
        $user->email = 'mike@thomas.com';
        $user->password = bcrypt('secret');
        $user->save();
        $user->assignRole($userRole);
    }
}
