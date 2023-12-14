<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use App\Http\Masters\Geo\Requests\RegionRequest;
use App\Http\Masters\Geo\Repositories\RegionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Responses\Lists\RegionListResponse;
use App\Http\Masters\Geo\Responses\Table\RegionTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Geo\Responses\RegionBulKInsertResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class RegionService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param RegionRepository $repository
     *
     * @return void
     */
    public function __construct(RegionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return RegionTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new RegionTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Region in the DB
     *
     * @param RegionRequest $data
     *
     * @return Array
     */
    public function add(RegionRequest $data )
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
     * Render the edit view for the Region model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findRegionById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new RegionListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param RegionRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RegionRequest $request, int $id)
    {
        // Retrieve the Region from the database
       try {
            $region = $this->repository->findById($id);
            if ($region) {
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
            return collect(new RegionBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Region record to 'rejected'.
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
     * Update the status of an Region record to 'Active'.
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
     * Update the status of an Region record to 'Approved'.
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
