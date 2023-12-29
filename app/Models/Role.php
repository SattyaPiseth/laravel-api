<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

/**
 * @method static firstOrCreate(array $array)
 */
class Role extends BaseRole
{
    public const ADMIN = 'Admin';
    public const USER = 'User';

    /**
     * @return array<string>
     */
    public static function allRoles(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];
    }
}
