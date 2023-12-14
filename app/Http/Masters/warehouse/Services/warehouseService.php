<?php

namespace App\Http\Masters\warehouse\Services;

use Exception;
use App\Http\Masters\Geo\Requests\LocationRequest;
use App\Http\Modules\CaseValidation\Models\Warehouse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\LocationRepository;
use App\Http\Masters\warehouse\Requests\warehouseRequest;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\warehouse\Responses\Lists\warehouseListResponse;
use App\Http\Masters\warehouse\Responses\WarehouseBulKInsertResponse;
use App\Http\Modules\CaseValidation\Repositories\WarehouseRepository;
use App\Http\Masters\warehouse\Responses\WarehouseTypeDropdownResponse;
use App\Http\Masters\warehouse\Responses\Table\warehouseTableCollection;

class warehouseService
{
    protected $repository, $locationRepository, $dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param WarehouseRepository $repository
     *
     * @return void
     */
    public function __construct(WarehouseRepository $repository,LocationRepository $locationRepository,DropdownRepository $dropdownRepository)
    {
        $this->repository = $repository;
        $this->locationRepository = $locationRepository;
        $this->dropdownRepository = $dropdownRepository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return warehouseTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $warehouseDropdownResponse = new WarehouseTypeDropdownResponse();
            $warehouseDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findWarehouseType());
            $warehouseDropdownResponse = $warehouseDropdownResponse->formFieldAtributes;
           return new warehouseTableCollection($this->repository->find($request),$warehouseDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new warehouse in the DB
     *
     * @param warehouseRequest $data
     *
     * @return Array
     */
    public function add(warehouseRequest $data )
    {
        try {
            $locationData = collect(
                [
                    'address' => $data->address,
                    'pincode' => $data->pincode
                ]
            )->all();
            $locationrequest = new LocationRequest($locationData);
            $location_id = $this->locationRepository->add($locationrequest);
            $data->merge(['location_id' => $location_id]);
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
     * Render the edit view for the warehouse model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findwarehouseById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new warehouseListResponse($this->repository->findwarehouseById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param warehouseRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(warehouseRequest $request, int $id)
    {
        // Retrieve the warehouse from the database
       try {
            $warehouse = $this->repository->findById($id);
            if ($warehouse) {
                $location = $this->locationRepository->findById($warehouse->location_id);
                $locationData = collect(
                    [
                        'address' => $request->address,
                        'pincode' => $request->pincode
                    ]
                )->all();
                $locationrequest = new LocationRequest($locationData);
                if($location){
                    $this->locationRepository->update($locationrequest, $warehouse->location_id);
                }
                $request->merge(['location_id' => $warehouse->location_id]);
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
            $warehouseDropdownResponse = new WarehouseTypeDropdownResponse();
            $warehouseDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findWarehouseType());
            $warehouseDropdownResponse = $warehouseDropdownResponse->formFieldAtributes;
            $final = array_merge([$warehouseDropdownResponse],$formResponse);
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
            return collect(new WarehouseBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an warehouse record to 'rejected'.
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
     * Update the status of an warehouse record to 'Active'.
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
     * Update the status of an warehouse record to 'Approved'.
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
