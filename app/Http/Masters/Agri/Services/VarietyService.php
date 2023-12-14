<?php

namespace App\Http\Masters\Agri\Services;

use Exception;
use App\Http\Masters\Agri\Requests\VarietyRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\VarietyRepository;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Agri\Responses\CommodityDropdownResponse;
use App\Http\Masters\Agri\Responses\Lists\VarietyListResponse;
use App\Http\Masters\Agri\Responses\VarietyBulKInsertResponse;
use App\Http\Masters\Agri\Responses\Table\VarietyTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class VarietyService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param VarietyRepository $repository
     *
     * @return void
     */
    public function __construct(VarietyRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return VarietyTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $commodityDropdownResponse = new CommodityDropdownResponse();
            $commodityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCommodity());
            $commodityDropdownResponse->formFieldAtributes['validators']['required'] = false;
            $commodityDropdownResponse = $commodityDropdownResponse->formFieldAtributes;
           return new VarietyTableCollection($this->repository->find($request),$commodityDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Variety in the DB
     *
     * @param VarietyRequest $data
     *
     * @return Array
     */
    public function add(VarietyRequest $data )
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
     * Render the edit view for the Variety model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findVarietyById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new VarietyListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param VarietyRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VarietyRequest $request, int $id)
    {
        // Retrieve the Variety from the database
       try {
            $variety = $this->repository->findById($id);
            if ($variety) {
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
            $commodityDropdownResponse = new CommodityDropdownResponse();
            $commodityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCommodity());
            $commodityDropdownResponse = $commodityDropdownResponse->formFieldAtributes;
            $final = array_merge([$commodityDropdownResponse],$formResponse);
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
            return collect(new VarietyBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Variety record to 'rejected'.
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
     * Update the status of an Variety record to 'Active'.
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
     * Update the status of an Variety record to 'Approved'.
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
