<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use GuzzleHttp\Psr7\Request;
use App\Http\Masters\Geo\Requests\CountryRequest;
use App\Http\Masters\Geo\Responses\Table\CountryBulKInsertResponse ;
use App\Http\Masters\Geo\Repositories\CountryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Responses\Lists\CountryListResponse;
use App\Http\Masters\Geo\Responses\Table\CountryTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CountryService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param CountryRepository $repository
     *
     * @return void
     */
    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CountryTableCollection
     */
    public function getAllPaginatedTableData($request)
    {

        // Return in the given API resource format
        try {
            return new CountryTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Country in the DB
     *
     * @param CountryRequest $data
     *
     * @return Array
     */
    public function add(CountryRequest $data)
    {
        try {
            return $this->repository->add($data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Country model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findCountryById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new CountryListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CountryRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CountryRequest $request, int $id)
    {
        // Retrieve the Country from the database
        try {
            $country = $this->repository->findById($id);
            if ($country) {
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
            return collect(new CountryBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'rejected'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateRejectStatus(array $id)
    {
        try {
            return $this->repository->updateStatusReject(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'Active'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateFinalizeStatus(array $id)
    {
        try {
            return $this->repository->updateStatusFinalize(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'Approved'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateApproveStatus(array $id)
    {
        try {
            return $this->repository->updateStatusApprove(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
