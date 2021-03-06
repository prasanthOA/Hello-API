<?php

namespace App\Containers\Authorization\UI\API\Tests\Functional;

use App\Containers\Authorization\Models\Role;
use App\Containers\Authorization\Tests\TestCase;
use App\Containers\User\Models\User;

/**
 * Class SyncUserRolesTest.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
class SyncUserRolesTest extends TestCase
{

    protected $endpoint = '/roles/sync';

    protected $access = [
        'roles'       => 'admin',
        'permissions' => '',
    ];

    public function testSyncMultipleRolesOnUser()
    {
        $this->getTestingUser();

        $role1 = factory(Role::class)->create(['display_name' => '111']);
        $role2 = factory(Role::class)->create(['display_name' => '222']);

        $randomUser = factory(User::class)->create();
        $randomUser->assignRole($role1);


        $data = [
            'roles_ids' => [
                $role1->getHashedKey(),
                $role2->getHashedKey(),
            ],
            'user_id'   => $randomUser->getHashedKey(),
        ];

        // send the HTTP request
        $response = $this->apiCall($this->endpoint, 'post', $data, true);

        // assert response status is correct
        $this->assertEquals('200', $response->getStatusCode());

        $responseObject = $this->getResponseObject($response);

        $this->assertTrue(count($responseObject->data->roles->data) > 1);

        $this->assertEquals($data['roles_ids'][0], $responseObject->data->roles->data[0]->id);

        $this->assertEquals($data['roles_ids'][1], $responseObject->data->roles->data[1]->id);
    }

}
