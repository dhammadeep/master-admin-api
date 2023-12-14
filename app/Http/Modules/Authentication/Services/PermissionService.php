<?php

namespace App\Http\Modules\Authentication\Services;

use Exception;
use App\Http\Modules\Authentication\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\PermissionRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\Authentication\Repositories\PermissionRepository;
use App\Http\Modules\Authentication\Responses\Lists\PermissionListResponse;
use App\Http\Modules\Authentication\Responses\Table\PermissionTableCollection;



class PermissionService
{
    private $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createPermission(PermissionRequest $data)
    {
        return $this->repository->createPermission($data);
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CountryTableCollection
     */
    public function getAllPaginatedTableData($request)
    {

        // Return in the given API resource format
        try {
            return new PermissionTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return $this->repository->getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Role model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findRoleById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new PermissionListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(PermissionRequest $request, int $id)
    {
        try {
            $data = $this->repository->update($request, $id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        return $data;
    }
}
