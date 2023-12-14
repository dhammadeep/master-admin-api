<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\RejectionReasonRequest;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Gen\Responses\Lists\RejectionReasonListResponse;
use App\Http\Masters\Gen\Responses\RejectionReasonTypeDropdownResponse;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonTableCollection;
use App\Http\Masters\Gen\Responses\Table\RejectionReasonBulKInsertResponse;


class RejectionReasonService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param RejectionReasonRepository $repository
     *
     * @return void
     */
    public function __construct(RejectionReasonRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return RejectionReasonTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $rejectionReasonTypeDropdownResponse = new RejectionReasonTypeDropdownResponse();
            $rejectionReasonTypeDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findRejectionReasonType());
            $rejectionReasonTypeDropdownResponse = $rejectionReasonTypeDropdownResponse->formFieldAtributes;
           return new RejectionReasonTableCollection($this->repository->find($request),$rejectionReasonTypeDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new RejectionReason in the DB
     *
     * @param RejectionReasonRequest $data
     *
     * @return Array
     */
    public function add(RejectionReasonRequest $data )
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
     * Render the edit view for the RejectionReason model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findRejectionReasonById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new RejectionReasonListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param RejectionReasonRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RejectionReasonRequest $request, int $id)
    {
        // Retrieve the RejectionReason from the database
       try {
            $rejectionReason = $this->repository->findById($id);
            if ($rejectionReason) {
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
            $rejectionReasonTypeDropdownResponse = new RejectionReasonTypeDropdownResponse();
            $rejectionReasonTypeDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findRejectionReasonType());
            $rejectionReasonTypeDropdownResponse = $rejectionReasonTypeDropdownResponse->formFieldAtributes;
            $final = array_merge([$rejectionReasonTypeDropdownResponse],[$formResponse[0]]);
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
            return collect(new RejectionReasonBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an RejectionReason record to 'rejected'.
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
     * Update the status of an RejectionReason record to 'Active'.
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
     * Update the status of an RejectionReason record to 'Approved'.
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
