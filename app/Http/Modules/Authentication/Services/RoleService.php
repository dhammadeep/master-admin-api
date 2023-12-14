<?php

namespace App\Http\Modules\Authentication\Services;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\RoleRequest;
use App\Http\Modules\Authentication\Repositories\RoleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\Authentication\Responses\Lists\RoleListResponse;
use App\Http\Modules\Authentication\Requests\ActivityPermissionRequest;
use App\Http\Modules\Authentication\Responses\Table\RoleTableCollection;
use App\Http\Modules\Authentication\Repositories\ActivityPermissionRepository;

class RoleService
{
    protected $repository,$activityPermissionRepository;

    public function __construct(RoleRepository $repository,ActivityPermissionRepository $activityPermissionRepository)
    {
        $this->repository = $repository;
        $this->activityPermissionRepository = $activityPermissionRepository;
    }

    public function getAll($request)
    {
        try {
            return new RoleTableCollection($this->repository->getAll($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function add(RoleRequest $request)
    {
        try {
            if(is_object($request)) {
                $activityId = $this->repository->create($request);
                $request2 = new ActivityPermissionRequest();
                $request2->merge(
                    [
                        'permission_id' => $request->permission_id,
                        'activity_id' => $activityId
                    ]
                );
                if($activityId){
                    $this->activityPermissionRepository->add($request2);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function addPermissionsToRole($roleId, array $permissions)
    {
        return $this->repository->addPermissionsToRole($roleId, $permissions);
    }

    public function getRoleByID(int $id)
    {
        return $this->repository->getRoleByID($id);
    }

    public function update(RoleRequest $request, int $id)
    {
        try {
            $data = $this->repository->update($request, $id);
            $request2 = new ActivityPermissionRequest();
            $request2->merge(
                [
                    'permission_id' => $request->permission_id,
                    'activity_id' => $id
                ]
            );
            $this->activityPermissionRepository->update($request2, $id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        return $data;
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
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
            return collect(new RoleListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
