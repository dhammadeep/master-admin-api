<?php

namespace App\Http\Masters\Agri\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Agri\Requests\CommodityParameterRequest;
use App\Http\Masters\Agri\Responses\CommodityDropdownResponse;
use App\Http\Masters\Agri\Responses\ParameterDropdownResponse;
use App\Http\Masters\Agri\Responses\PhenophaseDropdownResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Agri\Repositories\CommodityParameterRepository;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Agri\Responses\CommodityParameterBulKInsertResponse;
use App\Http\Masters\Agri\Responses\Lists\CommodityParameterListResponse;
use App\Http\Masters\Agri\Responses\Table\CommodityParameterTableCollection;


class CommodityParameterService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param CommodityParameterRepository $repository
     *
     * @return void
     */
    public function __construct(CommodityParameterRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return CommodityParameterTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $commodityDropdownResponse = new CommodityDropdownResponse();
            $commodityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findCommodity());
            $commodityDropdownResponse->formFieldAtributes['validators']['required'] = false;
            $commodityDropdownResponse = $commodityDropdownResponse->formFieldAtributes;

            $phenophaseDropdownResponse = new PhenophaseDropdownResponse();
            $phenophaseDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findPhenophase());
            $phenophaseDropdownResponse = $phenophaseDropdownResponse->formFieldAtributes;

            $parameterDropdownResponse = new ParameterDropdownResponse();
            $parameterDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findParameter());
            $parameterDropdownResponse->formFieldAtributes['validators']['required'] = false;
            $parameterDropdownResponse = $parameterDropdownResponse->formFieldAtributes;

            $final = array_merge([$commodityDropdownResponse,$phenophaseDropdownResponse,$parameterDropdownResponse]);

           return new CommodityParameterTableCollection($this->repository->find($request),$final);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new CommodityParameter in the DB
     *
     * @param CommodityParameterRequest $data
     *
     * @return Array
     */
    public function add(CommodityParameterRequest $data )
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
     * Render the edit view for the CommodityParameter model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findCommodityParameterById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new CommodityParameterListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CommodityParameterRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommodityParameterRequest $request, int $id)
    {
        // Retrieve the CommodityParameter from the database
       try {
            $commodityParameter = $this->repository->findById($id);
            if ($commodityParameter) {
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

            $phenophaseDropdownResponse = new PhenophaseDropdownResponse();
            $phenophaseDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findPhenophase());
            $phenophaseDropdownResponse = $phenophaseDropdownResponse->formFieldAtributes;

            $parameterDropdownResponse = new ParameterDropdownResponse();
            $parameterDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findParameter());
            $parameterDropdownResponse = $parameterDropdownResponse->formFieldAtributes;


            $final = array_merge([$commodityDropdownResponse],[$phenophaseDropdownResponse],$formResponse,[$parameterDropdownResponse]);
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
            return collect(new CommodityParameterBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an CommodityParameter record to 'rejected'.
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
     * Update the status of an CommodityParameter record to 'Active'.
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
     * Update the status of an CommodityParameter record to 'Approved'.
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
