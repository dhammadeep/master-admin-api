<?php

namespace App\Http\Masters\Warehouse\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Warehouse\Requests\WarehouseTypeRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Warehouse\Repositories\WarehouseTypeRepository;
use App\Http\Masters\Warehouse\Responses\Lists\WarehouseTypeListResponse;
use App\Http\Masters\warehouse\Responses\WarehouseTypeBulKInsertResponse;
use App\Http\Masters\Warehouse\Responses\Table\WarehouseTypeTableCollection;


class WarehouseTypeService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param WarehouseTypeRepository $repository
     *
     * @return void
     */
    public function __construct(WarehouseTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return WarehouseTypeTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new WarehouseTypeTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new WarehouseType in the DB
     *
     * @param WarehouseTypeRequest $data
     *
     * @return Array
     */
    public function add(WarehouseTypeRequest $data )
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
     * Render the edit view for the WarehouseType model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findWarehouseTypeById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new WarehouseTypeListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param WarehouseTypeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(WarehouseTypeRequest $request, int $id)
    {
        // Retrieve the WarehouseType from the database
       try {
            $warehouseType = $this->repository->findById($id);
            if ($warehouseType) {
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
            return collect(new WarehouseTypeBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an WarehouseType record to 'rejected'.
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
     * Update the status of an WarehouseType record to 'Active'.
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
     * Update the status of an WarehouseType record to 'Approved'.
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
