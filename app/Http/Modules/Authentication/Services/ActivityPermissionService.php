<?php

namespace App\Http\Modules\Authentication\Services;

use Exception;
use App\Http\Modules\Authentication\Requests\ActivityPermissionRequest;
use App\Http\Modules\Authentication\Repositories\ActivityPermissionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Responses\Lists\ActivityPermissionListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\Authentication\Responses\Table\ActivityPermissionTableCollection;


class ActivityPermissionService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param ActivityPermissionRepository $repository
     *
     * @return void
     */
    public function __construct(ActivityPermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return ActivityPermissionTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new ActivityPermissionTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new ActivityPermission in the DB
     *
     * @param ActivityPermissionRequest $data
     *
     * @return Array
     */
    public function add(ActivityPermissionRequest $data )
    {
        try {
            return $this->repository->add($data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }  catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the ActivityPermission model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findActivityPermissionById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new ActivityPermissionListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ActivityPermissionRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ActivityPermissionRequest $request, int $id)
    {
        // Retrieve the ActivityPermission from the database
       try {
            $activityPermission = $this->repository->findById($id);
            if ($activityPermission) {
                return $this->repository->update($request, $id);
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic table columns
     *
     * @return array
     */
    public function getTableFields(): array
    {
        try {
            return $this->repository->getTableFields();
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
     * Get the bulk insert form elements and data
     *
     * @return array
     */
    // public function getBulkInsertFormFields(): array
    // {
    //     try {
    //         return collect(new ActivityPermissionBulKInsertResponse([]))->all();
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    // }

    /**

     * Update the status of an ActivityPermission record to 'rejected'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateRejectStatus(array $id)
    {
        try {
            return $this->repository->updateStatusReject(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an ActivityPermission record to 'Active'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateFinalizeStatus(array $id)
    {
        try{
            return $this->repository->updateStatusFinalize(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an ActivityPermission record to 'Approved'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateApproveStatus(array $id)
    {
        try{
            return $this->repository->updateStatusApprove(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }
}
