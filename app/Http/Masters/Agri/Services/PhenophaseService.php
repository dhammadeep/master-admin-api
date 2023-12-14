<?php

namespace App\Http\Masters\Agri\Services;

use Exception;
use App\Http\Masters\Agri\Requests\PhenophaseRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\PhenophaseRepository;
use App\Http\Masters\Agri\Responses\Lists\PhenophaseListResponse;
use App\Http\Masters\Agri\Responses\PhenophaseBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Agri\Responses\Table\PhenophaseTableCollection;


class PhenophaseService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param PhenophaseRepository $repository
     *
     * @return void
     */
    public function __construct(PhenophaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return PhenophaseTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new PhenophaseTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Phenophase in the DB
     *
     * @param PhenophaseRequest $data
     *
     * @return Array
     */
    public function add(PhenophaseRequest $data )
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
     * Render the edit view for the Phenophase model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findPhenophaseById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new PhenophaseListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param PhenophaseRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PhenophaseRequest $request, int $id)
    {
        // Retrieve the Phenophase from the database
       try {
            $phenophase = $this->repository->findById($id);
            if ($phenophase) {
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
            return collect(new PhenophaseBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Phenophase record to 'rejected'.
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
     * Update the status of an Phenophase record to 'Active'.
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
     * Update the status of an Phenophase record to 'Approved'.
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
