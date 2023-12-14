<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Models\Bank;
use App\Http\Masters\Gen\Models\BankBranch;
use App\Http\Masters\Gen\Requests\BankBranchRequest;
use App\Http\Masters\Gen\Responses\BankDropdownResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Repositories\BankBranchRepository;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Gen\Responses\Lists\BankBranchListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Gen\Responses\Table\BankBranchTableCollection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Gen\Responses\BankBranchBulKInsertResponse;

class BankBranchService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param BankBranchRepository $repository
     *
     * @return void
     */
    public function __construct(BankBranchRepository $repository, DropdownRepository $dropdownRepository)
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
     * @return BankBranchTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $bankDropdownResponse = new BankDropdownResponse();
            $bankDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findBank());
            $bankDropdownResponse = $bankDropdownResponse->formFieldAtributes;
           return new BankBranchTableCollection($this->repository->find($request),$bankDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new BankBranch in the DB
     *
     * @param BankBranchRequest $data
     *
     * @return Array
     */
    public function add(BankBranchRequest $data )
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
     * Render the edit view for the BankBranch model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findBankBranchById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new BankBranchListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param BankBranchRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BankBranchRequest $request, int $id)
    {
        // Retrieve the BankBranch from the database
       try {
            $bankBranch = $this->repository->findById($id);
            if ($bankBranch) {
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
            $bankDropdownResponse = new BankDropdownResponse();
            $bankDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findBank());
            $bankDropdownResponse = $bankDropdownResponse->formFieldAtributes;
            $final = array_merge([$bankDropdownResponse],$formResponse);
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
            return collect(new BankBranchBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an BankBranch record to 'rejected'.
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
     * Update the status of an BankBranch record to 'Active'.
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
     * Update the status of an BankBranch record to 'Approved'.
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

     /**
     * Update the status of an Bank record to 'Status update'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateStatus(array $id, string $status)
    {
        try{
            return $this->repository->updateStatus(array($id),$status);
        } catch(Exception $e) {
            throw $e;
        }
    }
}
