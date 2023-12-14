<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Requests\TenderTypeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Repositories\TenderTypeRepository;
use App\Http\Masters\Gen\Responses\Lists\TenderTypeListResponse;
use App\Http\Masters\Gen\Responses\TenderTypeBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Gen\Responses\Table\TenderTypeTableCollection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class TenderTypeService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param TenderTypeRepository $repository
     *
     * @return void
     */
    public function __construct(TenderTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return TenderTypeTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new TenderTypeTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new TenderType in the DB
     *
     * @param TenderTypeRequest $data
     *
     * @return Array
     */
    public function add(TenderTypeRequest $data )
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
     * Render the edit view for the TenderType model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findTenderTypeById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new TenderTypeListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param TenderTypeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TenderTypeRequest $request, int $id)
    {
        // Retrieve the TenderType from the database
       try {
            $tenderType = $this->repository->findById($id);
            if ($tenderType) {
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
            return collect(new TenderTypeBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an TenderType record to 'rejected'.
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
     * Update the status of an TenderType record to 'Active'.
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
     * Update the status of an TenderType record to 'Approved'.
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
