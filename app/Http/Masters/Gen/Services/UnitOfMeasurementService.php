<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Responses\UomDropdownResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\UnitOfMeasurementRequest;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Gen\Repositories\UnitOfMeasurementRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Gen\Responses\Lists\UnitOfMeasurementListResponse;
use App\Http\Masters\Gen\Responses\UnitOfMeasurementBulKInsertResponse;
use App\Http\Masters\Gen\Responses\Table\UnitOfMeasurementTableCollection;


class UnitOfMeasurementService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param UnitOfMeasurementRepository $repository
     *
     * @return void
     */
    public function __construct(UnitOfMeasurementRepository $repository,DropdownRepository $dropdownRepository)
    {
        $this->repository = $repository;
        $this->dropdownRepository = $dropdownRepository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return UnitOfMeasurementTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $uomTypeDropdownResponse = new UomDropdownResponse();
            $uomTypeDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findUomType());
            $uomTypeDropdownResponse = $uomTypeDropdownResponse->formFieldAtributes;
           return new UnitOfMeasurementTableCollection($this->repository->find($request),$uomTypeDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new UnitOfMeasurement in the DB
     *
     * @param UnitOfMeasurementRequest $data
     *
     * @return Array
     */
    public function add(UnitOfMeasurementRequest $data )
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
     * Render the edit view for the UnitOfMeasurement model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findUnitOfMeasurementById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new UnitOfMeasurementListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UnitOfMeasurementRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UnitOfMeasurementRequest $request, int $id)
    {
        // Retrieve the UnitOfMeasurement from the database
       try {
            $unitOfMeasurement = $this->repository->findById($id);
            if ($unitOfMeasurement) {
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
            $formResponse = $this->repository->getFormFields();
            $uomTypeDropdownResponse = new UomDropdownResponse();
            $uomTypeDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findUomType());
            $uomTypeDropdownResponse = $uomTypeDropdownResponse->formFieldAtributes;
            $final = array_merge([$uomTypeDropdownResponse],[$formResponse[0]]);
            return $final;
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
            return collect(new UnitOfMeasurementBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an UnitOfMeasurement record to 'rejected'.
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
     * Update the status of an UnitOfMeasurement record to 'Active'.
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
     * Update the status of an UnitOfMeasurement record to 'Approved'.
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
