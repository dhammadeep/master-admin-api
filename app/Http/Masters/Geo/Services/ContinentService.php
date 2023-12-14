<?php

namespace App\Http\Masters\Geo\Services;

use Exception;
use App\Http\Masters\Geo\Requests\ContinentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\ContinentRepository;
use App\Http\Masters\Geo\Responses\ContinentBulKInsertResponse;
use App\Http\Masters\Geo\Responses\Lists\ContinentListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Geo\Responses\Table\ContinentTableCollection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class ContinentService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param ContinentRepository $repository
     *
     * @return void
     */
    public function __construct(ContinentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return ContinentTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new ContinentTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Continent in the DB
     *
     * @param ContinentRequest $data
     *
     * @return Array
     */
    public function add(ContinentRequest $data )
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
     * Render the edit view for the Continent model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findContinentById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new ContinentListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ContinentRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ContinentRequest $request, int $id)
    {
        // Retrieve the Continent from the database
       try {
            $continent = $this->repository->findById($id);
            if ($continent) {
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
            return collect(new ContinentBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Continent record to 'rejected'.
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
     * Update the status of an Continent record to 'Active'.
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
     * Update the status of an Continent record to 'Approved'.
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
