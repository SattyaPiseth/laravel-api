<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * @method static firstOrCreate(array $array)
 * @method static where(\Closure $param)
 */
class Permission extends BasePermission
{

    public static array $modules = [
        'user',
        'role',
        'product',
        'category',
        'brand',
        'address',
    ];

    /**
     * @return array<string>
     */
    public static function defaultPermission(): array
    {
        $permissions = [];
        foreach (self::$modules as $module) {
            $permissions = array_merge($permissions, [
                "view_$module",
                "create_$module",
                "edit_$module",
                "remove_$module",
            ]);
        }
        return $permissions;
    }
}
