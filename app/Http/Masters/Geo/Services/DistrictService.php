<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use App\Http\Masters\Geo\Models\District;
use App\Http\Masters\Geo\Requests\DistrictRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\DistrictRepository;
use App\Http\Masters\Geo\Responses\StateDropdownResponse;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Geo\Responses\Lists\DistrictListResponse;
use App\Http\Masters\Geo\Responses\Table\DistrictTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Geo\Responses\Table\DistrictBulKInsertResponse;

class DistrictService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param DistrictRepository $repository
     *
     * @return void
     */
    public function __construct(DistrictRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return DistrictTableCollection
     */
    public function getAllPaginatedTableData($request)
    {

        // Return in the given API resource format
        try {
            $stateDropdownResponse = new StateDropdownResponse();
            $stateDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findState());
            $stateDropdownResponse = $stateDropdownResponse->formFieldAtributes;
           return new DistrictTableCollection($this->repository->find($request),$stateDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new District in the DB
     *
     * @param DistrictRequest $data
     *
     * @return Array
     */
    public function add(DistrictRequest $data )
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
     * Render the edit view for the District model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findDistrictById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new DistrictListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param DistrictRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DistrictRequest $request, int $id)
    {
        // Retrieve the District from the database
       try {
            $district = $this->repository->findById($id);
            if ($district) {
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
            $stateDropdownResponse = new StateDropdownResponse();
            $stateDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findState());
            $stateDropdownResponse = $stateDropdownResponse->formFieldAtributes;
            $final = array_merge([$stateDropdownResponse],[$formResponse[0]]);
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
            return collect(new DistrictBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an District record to 'rejected'.
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
     * Update the status of an District record to 'Active'.
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
     * Update the status of an District record to 'Approved'.
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
