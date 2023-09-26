<?php

namespace Lkt;

use Lkt\Exceptions\PermActionNotDefinedException;
use Lkt\Exceptions\PermRoleNotDefinedException;

class Perm
{
    protected static array $perms = [];

    protected string $component;
    protected array $config = [];

    public function __construct(string $component)
    {
        $this->component = $component;
    }

    public static function component(string $component): static
    {
        if (!array_key_exists($component, static::$perms)) static::$perms[$component] = new static($component);
        return static::$perms[$component];
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grant(string $role, string $action): static
    {
        Role::checkIfRoleDefined($role);
        PermAction::checkIfActionDefined($action);
        if (!array_key_exists($role, $this->config)) {
            $this->config[$role] = [$action => true];
            return $this;
        }

        $this->config[$role][$action] = true;
        return $this;
    }

    public function grantMany(array $roles, array $actions): static
    {
        foreach ($roles as $role) foreach ($actions as $action) $this->grant($role, $action);
        return $this;
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grantCreate(string $role): static
    {
        return $this->grant($role, PermAction::CREATE);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grantRead(string $role): static
    {
        return $this->grant($role, PermAction::READ);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grantUpdate(string $role): static
    {
        return $this->grant($role, PermAction::UPDATE);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grantDelete(string $role): static
    {
        return $this->grant($role, PermAction::DELETE);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function grantCRUD(string $role): static
    {
        return $this
            ->grantCreate($role)
            ->grantRead($role)
            ->grantUpdate($role)
            ->grantDelete($role);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function hasPerm($role, $action): bool
    {
        Role::checkIfRoleDefined($role);
        PermAction::checkIfActionDefined($action);
        if (!array_key_exists($role, $this->config) || !array_key_exists($action, $this->config[$role])) return false;
        return $this->config[$role][$action] === true;
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function hasCreatePerm($role): bool
    {
        return $this->hasPerm($role, PermAction::CREATE);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function hasReadPerm($role): bool
    {
        return $this->hasPerm($role, PermAction::READ);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function hasUpdatePerm($role): bool
    {
        return $this->hasPerm($role, PermAction::UPDATE);
    }

    /**
     * @throws PermActionNotDefinedException
     * @throws PermRoleNotDefinedException
     */
    public function hasDeletePerm($role): bool
    {
        return $this->hasPerm($role, PermAction::DELETE);
    }
}