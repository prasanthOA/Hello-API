<?php

namespace App\Containers\Authorization\UI\API\Tests\Functional;

use App\Containers\Authorization\Models\Permission;
use App\Containers\Authorization\Models\Role;
use App\Containers\Authorization\Tests\TestCase;

/**
 * Class AttachPermissionsToRoleTest.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
class AttachPermissionsToRoleTest extends TestCase
{

    protected $endpoint = '/permissions/attach';

    protected $access = [
        'roles'       => 'admin',
        'permissions' => '',
    ];

    public function testAttachSinglePermissionToRole_()
    {
        $this->getTestingAdmin();

        $roleA = factory(Role::class)->create();
        $permissionA = factory(Permission::class)->create();

        $data = [
            'role_id'         => $roleA->getHashedKey(),
            'permissions_ids' => $permissionA->getHashedKey(),
        ];

        // send the HTTP request
        $response = $this->apiCall($this->endpoint, 'post', $data, true);

        // assert response status is correct
        $this->assertEquals('200', $response->getStatusCode());

        $responseObject = $this->getResponseObject($response);

        $this->assertEquals($roleA['name'], $responseObject->data->name);

        $this->seeInDatabase('role_has_permissions', [
            'permission_id' => $permissionA->id,
            'role_id'       => $roleA->id
        ]);
    }

    public function testAttachMultiplePermissionToRole_()
    {
        $this->getTestingAdmin();

        $roleA = factory(Role::class)->create();

        $permissionA = factory(Permission::class)->create();
        $permissionB = factory(Permission::class)->create();

        $data = [
            'role_id'         => $roleA->getHashedKey(),
            'permissions_ids' => [$permissionA->getHashedKey(), $permissionB->getHashedKey()]
        ];

        // send the HTTP request
        $response = $this->apiCall($this->endpoint, 'post', $data, true);

        // assert response status is correct
        $this->assertEquals('200', $response->getStatusCode());

        $this->seeInDatabase('role_has_permissions', [
            'permission_id' => $permissionA->id,
            'permission_id' => $permissionB->id,
            'role_id'       => $roleA->id
        ]);

    }

}
