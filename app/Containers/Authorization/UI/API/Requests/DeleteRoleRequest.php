<?php

namespace App\Containers\Authorization\UI\API\Requests;

use App\Ship\Parents\Requests\Request;

/**
 * Class GetRoleRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteRoleRequest extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request..
     *
     * @var  array
     */
    protected $access = [
        'roles'       => 'admin',
        'permissions' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [
        'id',
    ];

    /**
     * Defining the URL parameters (`/stores/999/items`) allows applying
     * validation rules on them and allows accessing them like request data.
     *
     * @var  array
     */
    protected $urlParameters = [
        'id',
    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:roles,id'
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        return $this->hasAccess();
    }
}
