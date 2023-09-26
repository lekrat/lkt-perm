<?php

namespace Lkt;

use Lkt\Exceptions\PermRoleNotDefinedException;

class Role
{
    public static array $roles = ['admin', 'editor', 'guest'];
    public static array $customRoles = [];

    public static function define(string $name): void
    {
        if (!in_array($name, static::$customRoles)) static::$customRoles[] = $name;
    }

    /**
     * @throws PermRoleNotDefinedException
     */
    public static function checkIfRoleDefined(string $role): void
    {
        if (!in_array($role, static::$roles) && !in_array($role, static::$customRoles)) throw PermRoleNotDefinedException::getInstance($role);
    }


}