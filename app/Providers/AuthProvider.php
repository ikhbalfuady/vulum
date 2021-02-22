<?php

namespace App\Providers;

use App\Models\Users;
use App\Models\Permissions;
use App\Models\UserPermissions;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{

    public static function has($request, $permission) {
        $user_id = H_JWT_getUserId($request);
        $selector = '
            users.id as user_id,
            users.role_id as role_id,
            role_permissions.permission_id as permission_id,
            permissions.slug as name,
            permissions.name as label,
            role_permissions.deleted_at as deleted_at
        ';

        $role_permissions = 'role_permissions';
        $permissions = 'permissions';

        $raw = Users::where('users.id', $user_id)->selectRaw($selector);

        $raw = $raw->join($role_permissions, function($join) use ($role_permissions)  {
            $join = $join->on($role_permissions . '.role_id', '=', 'users.role_id');
        });

        $raw = $raw->join($permissions, function($join) use ($permissions, $permission)  {
            $join = $join->on($permissions . '.id', '=', 'role_permissions.permission_id');
            $join = $join->where($permissions . '.slug', $permission);
            $join = $join->where('role_permissions.deleted_at', null);
        });

        $raw = $raw->first();
        $res = !empty($raw) ? true : false;
        if (!$res) echo H_api403(); 
    }

    public static function getPermissionUser ($request) {
        $user_id = H_JWT_getUserId($request);
        $selector = '
            users.id as user_id,
            users.role_id as role_id,
            role_permissions.permission_id as permission_id,
            permissions.slug as name,
            permissions.name as label
        ';

        $role_permissions = 'role_permissions';
        $permissions = 'permissions';

        $raw = Users::where('users.id', $user_id)->selectRaw($selector);

        $raw = $raw->join($role_permissions, function($join) use ($role_permissions)  {
            $join = $join->on($role_permissions . '.role_id', '=', 'users.role_id');
        });

        $raw = $raw->join($permissions, function($join) use ($permissions)  {
            $join = $join->on($permissions . '.id', '=', 'role_permissions.permission_id');
        });

        $raw = $raw->get();

        return H_toArrayObject($raw);
    }
}
