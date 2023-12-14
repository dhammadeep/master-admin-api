<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Models\RejectionReason;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\RejectionReasonTypeRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Gen\Repositories\RejectionReasonTypeRepository;
use App\Http\Masters\Gen\Responses\Lists\RejectionReasonTypeListResponse;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonTypeTableCollection;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonTypeBulKInsertResponse;


class RejectionReasonTypeService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param RejectionReasonTypeRepository $repository
     *
     * @return void
     */
    public function __construct(RejectionReasonTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return RejectionReasonTypeTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Return in the given API resource format
        try {
           return new RejectionReasonTypeTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new RejectionReasonType in the DB
     *
     * @param RejectionReasonTypeRequest $data
     *
     * @return Array
     */
    public function add(RejectionReasonTypeRequest $data )
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
     * Render the edit view for the RejectionReasonType model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findRejectionReasonTypeById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new RejectionReasonTypeListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param RejectionReasonTypeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RejectionReasonTypeRequest $request, int $id)
    {
        // Retrieve the RejectionReasonType from the database
       try {
            $rejectionReasonType = $this->repository->findById($id);
            if ($rejectionReasonType) {
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
            return collect(new RejectionReasonTypeBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an RejectionReasonType record to 'rejected'.
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
     * Update the status of an RejectionReasonType record to 'Active'.
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
     * Update the status of an RejectionReasonType record to 'Approved'.
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
