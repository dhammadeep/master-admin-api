<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Models\Bank;
use App\Http\Masters\Gen\Requests\BankRequest;
use App\Http\Masters\Gen\Repositories\BankRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Responses\Lists\BankListResponse;
use App\Http\Masters\Gen\Responses\Table\BankTableCollection;
use App\Http\Masters\Gen\Responses\BankBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class BankService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param BankRepository $repository
     *
     * @return void
     */
    public function __construct(BankRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return BankTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new BankTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Bank in the DB
     *
     * @param BankRequest $data
     *
     * @return Array
     */
    public function add(BankRequest $data )
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
     * Render the edit view for the Bank model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findBankById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new BankListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param BankRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BankRequest $request, int $id)
    {
        // Retrieve the Bank from the database
       try {
            $bank = $this->repository->findById($id);
            if ($bank) {
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
            return collect(new BankBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Bank record to 'rejected'.
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
     * Update the status of an Bank record to 'Active'.
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
     * Update the status of an Bank record to 'Approved'.
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
