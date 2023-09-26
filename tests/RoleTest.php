<?php

namespace tests;

use Lkt\Perm;
use Lkt\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testBuiltInRoles()
    {
        Role::define('admin2');

        // Check all perms given to a component for a role
        $componentLorem = Perm::component('lorem')->grantCRUD('admin');
        $this->assertTrue($componentLorem->hasCreatePerm('admin'));
        $this->assertTrue($componentLorem->hasReadPerm('admin'));
        $this->assertTrue($componentLorem->hasUpdatePerm('admin'));
        $this->assertTrue($componentLorem->hasDeletePerm('admin'));
        $this->assertFalse($componentLorem->hasCreatePerm('admin2'));
        $this->assertFalse($componentLorem->hasReadPerm('admin2'));
        $this->assertFalse($componentLorem->hasUpdatePerm('admin2'));
        $this->assertFalse($componentLorem->hasDeletePerm('admin2'));


        // Check no perms given to a component
        $componentLorem2 = Perm::component('lorem2');
        $this->assertFalse($componentLorem2->hasCreatePerm('admin2'));
        $this->assertFalse($componentLorem2->hasReadPerm('admin2'));
        $this->assertFalse($componentLorem2->hasUpdatePerm('admin2'));
        $this->assertFalse($componentLorem2->hasDeletePerm('admin2'));


        // Check different perms depending of role
        $componentIpsum = Perm::component('ipsum')
            ->grantCreate('editor')
            ->grantRead('editor')
            ->grantUpdate('editor')
            ->grantRead('guest')
        ;
        $this->assertTrue($componentIpsum->hasCreatePerm('editor'));
        $this->assertTrue($componentIpsum->hasReadPerm('editor'));
        $this->assertTrue($componentIpsum->hasUpdatePerm('editor'));
        $this->assertFalse($componentIpsum->hasDeletePerm('editor'));

        $this->assertFalse($componentIpsum->hasCreatePerm('guest'));
        $this->assertTrue($componentIpsum->hasReadPerm('guest'));
        $this->assertFalse($componentIpsum->hasUpdatePerm('guest'));
        $this->assertFalse($componentIpsum->hasDeletePerm('guest'));

        // Check invalid action
        $this->expectExceptionMessage("PermActionNotDefinedException: Perm action 'manage-account' not defined");
        $componentIpsum2 = Perm::component('ipsum2')
            ->grant('editor', 'manage-account');

        $this->assertFalse($componentIpsum2->hasPerm('guest', 'manage-account2'));
    }
}