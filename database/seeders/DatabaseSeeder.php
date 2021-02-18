<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use App\Models\MenuItems;
use App\Models\Menus;
use App\Models\MasterMenus;
use App\Models\Roles;
use App\Models\Permissions;
use App\Models\RolePermission;
use App\Models\RolePermissions;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::create([
            'name'  => 'boss',
            'username'  => 'boss',
            'email' => 'boss@mail.com',
            'menu_id' => 1,
            'role_id' => 1,
            'password'  => 'LxC61B52HvV/ce0ZjUNSHQ==' // P@ssw0rd
        ]);


        $menus = [
            [
                'parent_id' => null,
                'name' => 'Dashboard',
                'slug' => null,
                'path' => '/',
                'icon' => null,

            ],
            [
                'parent_id' => null,
                'name' => 'Users',
                'slug' => null,
                'path' => '/user',
                'icon' => null,

            ],
            [
                'parent_id' => 2,
                'name' => 'User List',
                'slug' => null,
                'path' => '/user',
                'icon' => null,

            ],
            [
                'parent_id' => 2,
                'name' => 'Add User',
                'slug' => null,
                'path' => '/user-form',
                'icon' => null,

            ],
            [
                'parent_id' => null,
                'name' => 'Permissions',
                'slug' => null,
                'path' => '/permission',
                'icon' => null,

            ],
            [
                'parent_id' => 5,
                'name' => 'Permission List',
                'slug' => null,
                'path' => '/permission',
                'icon' => null,

            ],
            [
                'parent_id' => 5,
                'name' => 'Add Permission',
                'slug' => null,
                'path' => '/permission-form',
                'icon' => null,

            ],
            [
                'parent_id' => null,
                'name' => 'Roles',
                'slug' => null,
                'path' => '/role',
                'icon' => null,

            ],
            [
                'parent_id' => 8,
                'name' => 'Role List',
                'slug' => null,
                'path' => '/role',
                'icon' => null,

            ],
            [
                'parent_id' => 8,
                'name' => 'Add Role',
                'slug' => null,
                'path' => '/role-form',
                'icon' => null,

            ],
            [
                'parent_id' => null,
                'name' => 'Menus',
                'slug' => null,
                'path' => '/menu',
                'icon' => null,

            ],
            [
                'parent_id' => 11,
                'name' => 'Menus List',
                'slug' => null,
                'path' => '/menu',
                'icon' => null,

            ],
            [
                'parent_id' => 11,
                'name' => 'Add Menus',
                'slug' => null,
                'path' => '/menu-form',
                'icon' => null,

            ],
            [
                'parent_id' => null,
                'name' => 'Master Menus',
                'slug' => null,
                'path' => '/master-menu',
                'icon' => null,

            ],
            [
                'parent_id' => 14,
                'name' => 'Master Menu List',
                'slug' => null,
                'path' => '/master-menu',
                'icon' => null,

            ],
            [
                'parent_id' => 14,
                'name' => 'Add Master Menu',
                'slug' => null,
                'path' => '/master-menu-form',
                'icon' => null,

            ]
        ];

        MasterMenus::create([
            'name' => 'Default',
        ]);

        Roles::create([
            'name' => 'Default',
            'slug' => 'default',
        ]);

        foreach ($menus as $key => $menu) {
            $slug = $menu['slug'];
            if (empty($slug)) $slug = strtolower(str_replace(' ', '-', $menu['name']));

            // generatin menus
            $menu = MenuItems::create([
                'parent_id' => $menu['parent_id'],
                'name' => $menu['name'],
                'slug' => $slug,
                'path' => $menu['path'],
                'icon' => $menu['icon'],
            ]);

            Menus::create([
                'menu_item_id' => $menu->id,
                'master_menu_id' => 1,
            ]);

            // generating roles permissions
            $permission = Permissions::create([
                'name' => $menu['name'],
                'slug' => $slug,
            ]);

            RolePermissions::create([
                'permission_id' => $permission->id,
                'role_id' => 1,
            ]);

        }

    }
}
