<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use App\Models\MenuItems;
use App\Models\Menus;
use App\Models\MasterMenus;
use App\Models\Roles;
use App\Models\Permissions;
use App\Models\RolePermissions;
use App\Models\UserNotifications;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->DefaultData();
        
    }

    public function DefaultData () {
        Users::create([
            'name'  => 'boss',
            'username'  => 'boss',
            'email' => 'boss@mail.com',
            'menu_id' => 1,
            'role_id' => 1,
            'password'  => 'LxC61B52HvV/ce0ZjUNSHQ==', // P@ssw0rd
            'active' => 1,
            'created_by' => 1,
        ]);

        $modules = [ // id start dari 2
            "Activity",
            "Users",
            "UserSessions",
            "Permissions", 
            "Roles",
            "MenuItems",
            "MasterMenus",
            "UserNotifications",
        ];
 
        $customPermission = [
            // to generate custom permission like : UsersImport
        ];

        // init permission
        $this->initPermission($modules, $customPermission);

        $masterRole = Roles::create([
            'name' => 'ADMINISTRATOR',
            'slug' => 'administrator',
            'code' => 'ADM',
            'created_by' => 1,
        ]);

        // init menus
        $customMenu = [
            // to generate custom menu like : MasterData, Config
        ];

        $menuLists = array_merge($modules, $customMenu);
        // sort($menuLists);
        $this->initMenu($menuLists);

        // create first notif
        UserNotifications::create([
            'user_id' => 1,
            'is_read' => 0,
            'title' => 'Welcome!',
            'description' => 'hope you enjoy use this app.',
            'type' => 'info',
            'link_path' => 'view-users',
            'link_params' => ['id' => 1],
            'created_by' => 1,
        ]);

    }

    public function initMenu($modules) {

        // init master menu
        $masterMenu = MasterMenus::create([
            'name' => 'Default',
            'created_by' => 1,
        ]);

        // init first menu : Home
        $menu = MenuItems::create([
            'name' => 'Home',
            'slug' => 'home',
            'path' => '/',
            'icon' => 'home',
            'created_by' => 1,
        ]);

        // injecting first menu to master menu
        Menus::create([
            'parent_id' => null,
            'menu_item_id' => $menu->id,
            'master_menu_id' => $masterMenu->id,
            'ordering' => 1,
            'created_by' => 1,
        ]);

        // generate menu from modules
        $order = 1;
        foreach ($modules as $name) {
            $fixName = H_splitUppercaseWithSpace($name);
            $slug = H_makeSlug($name);
            $path = '/' . $slug;

            // generatin menus
            $menu = MenuItems::create([
                'name' => $fixName,
                'slug' => $slug,
                'path' => $path,
                'icon' => null,
                'created_by' => 1,
                
            ]);
            
            Menus::create([
                'parent_id' => null,
                'menu_item_id' => $menu->id,
                'master_menu_id' => $masterMenu->id,
                'ordering' => $order + 1,
                'created_by' => 1,
            ]);

            $order ++;
        }


    }

    public function initPermission($modules, $custom = []) {

        $masterRole = Roles::create([
            'code' => 'dft',
            'name' => 'Default',
            'slug' => 'default',
            'created_by' => 1,
        ]);

        $inputPermissions = [];
        foreach ($modules as $name) {
            $fixName = H_splitUppercaseWithSpace($name);
            $slug = H_makeSlug($name);
            $crud = [ 'Browse', 'Create', 'Read', 'Update', 'Delete', 'Restore', ];

            foreach ($crud as $role) {
                $name = $fixName.' '.$role;
                $slugs = strtolower($slug.'-'.$role);
                $inputPermissions[] = [
                    'name' => $name,
                    'slug' => $slugs,
                    'created_by' => 1,
                ];
            }
        }

        foreach ($custom as $name) {
            $fixName = H_splitUppercaseWithSpace($name);
            $slug = H_makeSlug($name);
            $inputPermissions[] = [
                'name' => $fixName,
                'slug' => $slug,
                'created_by' => 1,
            ];
        }
        Permissions::insert($inputPermissions);

        $dataPermissions = Permissions::all();
        $inputRolePermissions = [];
        foreach ($dataPermissions as $r) $inputRolePermissions[] = [
            'permission_id' => $r->id,
            'role_id' => $masterRole->id,
            'created_by' => 1,
        ];
        RolePermissions::insert($inputRolePermissions);
    }

}
