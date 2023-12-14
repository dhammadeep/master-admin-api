<?php

namespace App\Http\Modules\Authentication\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\Authentication\Requests\RoleRequest;
use App\Http\Modules\Authentication\Services\RoleService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RoleController extends Controller
{
    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            // Check if the authenticated user has permission
            $data = $this->service->getAll($request);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }

    /**
     * Displays a form to add new roles
     *
     * @link http://roles/create/
     *
     * @method GET
     *
     * @return Response
     */
    public function create()
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $data = [
                'formData' => [
                    // 'type' => 'Add',
                    // 'title' => 'Add Geographical user',
                    'fields' => $this->service->getFormFields(),
                    'formStoreUrl' => 'roles'
                ]
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    public function store(RoleRequest $request)
    {
        $data = null;
        $code = "SUCCESS";
        // Validation and input handling
        try {
            // Check if the authenticated user has permission
            $this->service->add($request);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }

    /**
     * Displays a form with values to update user
     *
     * @link http://roles/edit/id
     *
     * @method GET
     *
     * @return Response
     */
    public function edit(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $tableData = $this->service->findRoleById($id);
            $data = collect($this->service->getFormFields())->map(function ($fields) use ($tableData) {
                $attrib = $fields['name'];
                if (isset($tableData[$attrib])) {
                    $fields['value'] = $tableData[$attrib];
                }else{
                    $fields['value'] = '';
                }
                return $fields;
            });
            $data = [
                'formData' => [
                    'fields' => $data,
                    'formStoreUrl' => "roles/$id"
                ]
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }
    }

    public function addPermissionsToRole(Request $request, $roleId)
    {
        $data = null;
        $code = "SUCCESS";

        $request->validate([
            'permission' => 'required|array',
        ]);

        try {
            $permissions = $request->input('permission');
            $data = $this->service->addPermissionsToRole($roleId, $permissions);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }

    public function show(int $id)
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->getRoleByID($id);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

        return ResponseHelper::respond(
            $code,
            $data,
        );
    }

    public function update(RoleRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->update($request, $id);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }

    public function destroy(int $id)
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->delete($id);
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }
}
