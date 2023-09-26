<?php

namespace Lkt;

use Lkt\Exceptions\PermActionNotDefinedException;

class PermAction
{
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';

    protected static array $actions = [
        self::CREATE,
        self::READ,
        self::UPDATE,
        self::DELETE,
    ];
    protected static array $customActions = [];

    public static function define(string $name): void
    {
        if (!in_array($name, static::$customActions)) static::$customActions[] = $name;
    }

    /**
     * @throws PermActionNotDefinedException
     */
    public static function checkIfActionDefined(string $action): void
    {
        if (!in_array($action, static::$actions) && !in_array($action, static::$customActions)) throw PermActionNotDefinedException::getInstance($action);
    }
}