<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\UnitOfMeasurementTypeRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Gen\Repositories\UnitOfMeasurementTypeRepository;
use App\Http\Masters\Gen\Responses\Lists\UnitOfMeasurementTypeListResponse;
use App\Http\Masters\Gen\Responses\UnitOfMeasurementTypeBulKInsertResponse;
use App\Http\Masters\Gen\Responses\Table\UnitOfMeasurementTypeTableCollection;


class UnitOfMeasurementTypeService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param UnitOfMeasurementTypeRepository $repository
     *
     * @return void
     */
    public function __construct(UnitOfMeasurementTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return UnitOfMeasurementTypeTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new UnitOfMeasurementTypeTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new UnitOfMeasurementType in the DB
     *
     * @param UnitOfMeasurementTypeRequest $data
     *
     * @return Array
     */
    public function add(UnitOfMeasurementTypeRequest $data )
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
     * Render the edit view for the UnitOfMeasurementType model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findUnitOfMeasurementTypeById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new UnitOfMeasurementTypeListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UnitOfMeasurementTypeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UnitOfMeasurementTypeRequest $request, int $id)
    {
        // Retrieve the UnitOfMeasurementType from the database
       try {
            $unitOfMeasurementType = $this->repository->findById($id);
            if ($unitOfMeasurementType) {
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
            return collect(new UnitOfMeasurementTypeBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an UnitOfMeasurementType record to 'rejected'.
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
     * Update the status of an UnitOfMeasurementType record to 'Active'.
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
     * Update the status of an UnitOfMeasurementType record to 'Approved'.
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
