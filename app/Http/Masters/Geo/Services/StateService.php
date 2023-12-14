<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Geo\Requests\StateRequest;
use App\Http\Masters\Geo\Repositories\StateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Responses\CountryDropdownResponse;
use App\Http\Masters\Geo\Responses\Lists\StateListResponse;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Geo\Responses\Table\StateTableCollection;
use App\Http\Masters\Geo\Responses\Table\StateBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class StateService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param StateRepository $repository
     *
     * @return void
     */
    public function __construct(StateRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return StateTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $countryDropdownResponse = new CountryDropdownResponse();
            $countryDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCountry());
            $countryDropdownResponse = $countryDropdownResponse->formFieldAtributes;
           return new StateTableCollection($this->repository->find($request),$countryDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new State in the DB
     *
     * @param StateRequest $data
     *
     * @return Array
     */
    public function add(StateRequest $data )
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
     * Render the edit view for the State model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findStateById(int $id)
    {
        try {
            return collect(new StateListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param StateRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StateRequest $request, int $id)
    {
        // Retrieve the State from the database
       try {
            $state = $this->repository->findById($id);
            if ($state) {
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
    public function getFormFields()
    {
        try {
            $formResponse = $this->repository->getFormFields();
            $countryDropdownResponse = new CountryDropdownResponse();
            $countryDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCountry());
            $countryDropdownResponse = $countryDropdownResponse->formFieldAtributes;
            $final = array_merge([$countryDropdownResponse],[$formResponse[0]]);
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
            return collect(new StateBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an State record to 'rejected'.
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
     * Update the status of an State record to 'Active'.
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
     * Update the status of an State record to 'Approved'.
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
