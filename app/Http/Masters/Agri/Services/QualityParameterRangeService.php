<?php

namespace App\Http\Masters\Agri\Services;

use Exception;
use App\Http\Masters\Agri\Models\Quality;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Responses\QualityDropdownResponse;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Agri\Responses\CommodityDropdownResponse;
use App\Http\Masters\Agri\Responses\ParameterDropdownResponse;
use App\Http\Masters\Agri\Requests\QualityParameterRangeRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Agri\Repositories\QualityParameterRangeRepository;
use App\Http\Masters\Agri\Responses\Lists\QualityParameterRangeListResponse;
use App\Http\Masters\Agri\Responses\QualityParameterRangeBulKInsertResponse;
use App\Http\Masters\Agri\Responses\Table\QualityParameterRangeTableCollection;


class QualityParameterRangeService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param QualityParameterRangeRepository $repository
     *
     * @return void
     */
    public function __construct(QualityParameterRangeRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return QualityParameterRangeTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $commodityDropdownResponse = new CommodityDropdownResponse();
            $commodityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCommodity());
            $commodityDropdownResponse->formFieldAtributes['validators']['required'] = false;
            $commodityDropdownResponse = $commodityDropdownResponse->formFieldAtributes;


            $parameterDropdownResponse = new ParameterDropdownResponse();
            $parameterDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findParameter());
            $parameterDropdownResponse->formFieldAtributes['validators']['required'] = false;
            $parameterDropdownResponse = $parameterDropdownResponse->formFieldAtributes;

            $qualityDropdownResponse = new QualityDropdownResponse();
            $qualityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findQuality());
            $qualityDropdownResponse = $qualityDropdownResponse->formFieldAtributes;

            $final = array_merge([$commodityDropdownResponse,$parameterDropdownResponse,$qualityDropdownResponse]);
           return new QualityParameterRangeTableCollection($this->repository->find($request),$final);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new QualityParameterRange in the DB
     *
     * @param QualityParameterRangeRequest $data
     *
     * @return Array
     */
    public function add(QualityParameterRangeRequest $data )
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
     * Render the edit view for the QualityParameterRange model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findQualityParameterRangeById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new QualityParameterRangeListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param QualityParameterRangeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QualityParameterRangeRequest $request, int $id)
    {
        // Retrieve the QualityParameterRange from the database
       try {
            $qualityParameterRange = $this->repository->findById($id);
            if ($qualityParameterRange) {
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
            // return $this->repository->getFormFields();
            $formResponse = $this->repository->getFormFields();
            $commodityDropdownResponse = new CommodityDropdownResponse();
            $commodityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCommodity());
            $commodityDropdownResponse = $commodityDropdownResponse->formFieldAtributes;

            $paramterDropdownResponse = new ParameterDropdownResponse();
            $paramterDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findParameter());
            $paramterDropdownResponse = $paramterDropdownResponse->formFieldAtributes;

            $qualityDropdownResponse = new QualityDropdownResponse();
            $qualityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findQuality());
            $qualityDropdownResponse = $qualityDropdownResponse->formFieldAtributes;


            $final = array_merge([$commodityDropdownResponse],[$paramterDropdownResponse],[$qualityDropdownResponse],$formResponse);
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
            return collect(new QualityParameterRangeBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an QualityParameterRange record to 'rejected'.
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
     * Update the status of an QualityParameterRange record to 'Active'.
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
     * Update the status of an QualityParameterRange record to 'Approved'.
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
