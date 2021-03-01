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
			'name' => 'Users',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 1,
			'name' => 'Users List',
			'slug' => null,
			'path' => 'users',
			'icon' => null,

		],
		[
			'parent_id' => 1,
			'name' => 'Add Users',
			'slug' => null,
			'path' => 'users/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'User Sessions',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 4,
			'name' => 'User Sessions List',
			'slug' => null,
			'path' => 'user-sessions',
			'icon' => null,

		],
		[
			'parent_id' => 4,
			'name' => 'Add User Sessions',
			'slug' => null,
			'path' => 'user-sessions/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Permissions',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 7,
			'name' => 'Permissions List',
			'slug' => null,
			'path' => 'permissions',
			'icon' => null,

		],
		[
			'parent_id' => 7,
			'name' => 'Add Permissions',
			'slug' => null,
			'path' => 'permissions/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Role Permissions',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 10,
			'name' => 'Role Permissions List',
			'slug' => null,
			'path' => 'role-permissions',
			'icon' => null,

		],
		[
			'parent_id' => 10,
			'name' => 'Add Role Permissions',
			'slug' => null,
			'path' => 'role-permissions/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Roles',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 13,
			'name' => 'Roles List',
			'slug' => null,
			'path' => 'roles',
			'icon' => null,

		],
		[
			'parent_id' => 13,
			'name' => 'Add Roles',
			'slug' => null,
			'path' => 'roles/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Menu Items',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 16,
			'name' => 'Menu Items List',
			'slug' => null,
			'path' => 'menu-items',
			'icon' => null,

		],
		[
			'parent_id' => 16,
			'name' => 'Add Menu Items',
			'slug' => null,
			'path' => 'menu-items/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Menus',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 19,
			'name' => 'Menus List',
			'slug' => null,
			'path' => 'menus',
			'icon' => null,

		],
		[
			'parent_id' => 19,
			'name' => 'Add Menus',
			'slug' => null,
			'path' => 'menus/form',
			'icon' => null,

		],
        
        [
			'parent_id' => null,
			'name' => 'Master Menus',
			'slug' => null,
			'path' => '/',
			'icon' => null,

		],
		[
			'parent_id' => 22,
			'name' => 'Master Menus List',
			'slug' => null,
			'path' => 'master-menus',
			'icon' => null,

		],
		[
			'parent_id' => 22,
			'name' => 'Add Master Menus',
			'slug' => null,
			'path' => 'master-menus/form',
			'icon' => null,

		],
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
            $parent = $menu['parent_id'];
            $menu = MenuItems::create([
                'name' => $menu['name'],
                'slug' => $slug,
                'path' => $menu['path'],
                'icon' => $menu['icon'],
                ]);
                
            Menus::create([
                'parent_id' => $parent,
                'menu_item_id' => $menu->id,
                'master_menu_id' => 1,
            ]);

            if ($parent == null && $menu['name'] != 'Dashboard') { // defined only head module generate permission

                $crud = [
                    'Browse',
                    'Create',
                    'Read',
                    'Update',
                    'Delete',
                    'Restore',
                ];

                foreach ($crud as $key => $role) {
                    // generating roles permissions
                    $slugs = strtolower($slug.'-'.$role);
                    $permission = Permissions::create([
                        'name' => $menu['name'] . ' ' . $role,
                        'slug' => $slugs,
                    ]);

                    RolePermissions::create([
                        'permission_id' => $permission->id,
                        'role_id' => 1,
                    ]);
                }
               
            }

            

        }

    }
}
