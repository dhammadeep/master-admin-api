<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use App\Http\Masters\Gen\Requests\StageVideoRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Responses\StageDropdownResponse;
use App\Http\Masters\Gen\Repositories\StageVideoRepository;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Gen\Responses\Lists\StageVideoListResponse;
use App\Http\Masters\Gen\Responses\StageVideoBulKInsertResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Gen\Responses\Table\StageVideoTableCollection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class StageVideoService
{
    protected $repository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param StageVideoRepository $repository
     *
     * @return void
     */
    public function __construct(StageVideoRepository $repository, DropdownRepository $dropdownRepository)
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
     * @return StageVideoTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $stageDropdownResponse = new StageDropdownResponse();
            $stageDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findStage());
            $stageDropdownResponse = $stageDropdownResponse->formFieldAtributes;
           return new StageVideoTableCollection($this->repository->find($request),$stageDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new StageVideo in the DB
     *
     * @param StageVideoRequest $data
     *
     * @return Array
     */
    public function add(StageVideoRequest $data )
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
     * Render the edit view for the StageVideo model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findStageVideoById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new StageVideoListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param StageVideoRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StageVideoRequest $request, int $id)
    {
        // Retrieve the StageVideo from the database
       try {
            $stageVideo = $this->repository->findById($id);
            if ($stageVideo) {
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
            $stageDropdownResponse = new StageDropdownResponse();
            $stageDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findStage());
            $stageDropdownResponse = $stageDropdownResponse->formFieldAtributes;
            $final = array_merge([$stageDropdownResponse],$formResponse);
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
            return collect(new StageVideoBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an StageVideo record to 'rejected'.
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
     * Update the status of an StageVideo record to 'Active'.
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
     * Update the status of an StageVideo record to 'Approved'.
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
