<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use App\Http\Masters\Geo\Requests\LocationRequest;
use App\Http\Masters\Geo\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Responses\Lists\LocationListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Geo\Responses\Table\LocationTableCollection;


class LocationService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param LocationRepository $repository
     *
     * @return void
     */
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return LocationTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new LocationTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Location in the DB
     *
     * @param LocationRequest $data
     *
     * @return Array
     */
    public function add(LocationRequest $data )
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
     * Render the edit view for the Location model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findLocationById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new LocationListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param LocationRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LocationRequest $request, int $id)
    {
        // Retrieve the Location from the database
       try {
            $location = $this->repository->findById($id);
            if ($location) {
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
    public function getBulkInsertFormFields(): array
    {
        try {
            return collect(new LocationBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Location record to 'rejected'.
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
     * Update the status of an Location record to 'Active'.
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
     * Update the status of an Location record to 'Approved'.
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
