<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use App\Http\Masters\Geo\Requests\CityRequest;
use App\Http\Masters\Geo\Repositories\CityRepository;
use App\Http\Masters\Geo\Responses\CityDropdownResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Responses\Lists\CityListResponse;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Geo\Responses\Table\CityTableCollection;
use App\Http\Masters\Geo\Responses\Table\CityBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class CityService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param CityRepository $repository
     *
     * @return void
     */
    public function __construct(CityRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return CityTableCollection
     */
    public function getAllPaginatedTableData($request)
    {

        // Return in the given API resource format
        try {
            $districtDropdownResponse = new CityDropdownResponse();
            $districtDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findDistrict());
            $districtDropdownResponse = $districtDropdownResponse->formFieldAtributes;
           return new CityTableCollection($this->repository->find($request),$districtDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new City in the DB
     *
     * @param CityRequest $data
     *
     * @return Array
     */
    public function add(CityRequest $data )
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
     * Render the edit view for the City model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findCityById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new CityListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CityRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CityRequest $request, int $id)
    {
        // Retrieve the City from the database
       try {
            $city = $this->repository->findById($id);
            if ($city) {
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
            $districtDropdownResponse = new CityDropdownResponse();
            $districtDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findDistrict());
            $districtDropdownResponse = $districtDropdownResponse->formFieldAtributes;
            $final = array_merge([$districtDropdownResponse],[$formResponse[0]]);
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
            return collect(new CityBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an City record to 'rejected'.
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
     * Update the status of an City record to 'Active'.
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
     * Update the status of an City record to 'Approved'.
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
