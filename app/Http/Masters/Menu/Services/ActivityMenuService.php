<?php

namespace App\Http\Masters\Menu\Services;

use Exception;
use App\Http\Masters\Menu\Repositories\MenuRepository;
use App\Http\Masters\Menu\Requests\ActivityMenuRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Menu\Responses\ActivityDropdownResponse;
use App\Http\Masters\Menu\Repositories\ActivityMenuRepository;
use App\Http\Masters\Menu\Responses\ActivityMenuDropdownResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Menu\Responses\Lists\ActivityMenuListResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;
use App\Http\Masters\Menu\Responses\Table\ActivityMenuTableCollection;
use App\Http\Masters\Menu\Responses\Table\ActivityMenuDropdownTableCollection;



class ActivityMenuService
{
    protected $repository, $menuRepository,$dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param ActivityMenuRepository $repository
     *
     * @return void
     */
    public function __construct(ActivityMenuRepository $repository,MenuRepository $menuRepository,DropdownRepository $dropdownRepository)
    {
        $this->repository = $repository;
        $this->menuRepository = $menuRepository;
        $this->dropdownRepository = $dropdownRepository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return ActivityMenuTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new ActivityMenuTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new ActivityMenu in the DB
     *
     * @param ActivityMenuRequest $data
     *
     * @return Array
     */
    public function add(ActivityMenuRequest $data )
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
     * Render the edit view for the ActivityMenu model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findActivityMenuById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new ActivityMenuListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ActivityMenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ActivityMenuRequest $request, int $id)
    {
        // Retrieve the ActivityMenu from the database
       try {
            $activityMenu = $this->repository->findById($id);
            if ($activityMenu) {
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
            $final = [];
            $menuDropdownResponse = new ActivityMenuDropdownResponse();
            $menuDropdownResponse->formFieldAtributes['options'] = new ActivityMenuDropdownTableCollection($this->menuRepository->getMenuAndSubmenu());
            $menuDropdownResponse = $menuDropdownResponse->formFieldAtributes;
            $activityDropdownResponse = new ActivityDropdownResponse();
            $activityDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findActivity());
            $activityDropdownResponse = $activityDropdownResponse->formFieldAtributes;
            $final = array_merge([$activityDropdownResponse],[$menuDropdownResponse]);
            return $final;
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Get the bulk insert form elements and data
     *
     * @return array
     */
    // public function getBulkInsertFormFields(): array
    // {
    //     try {
    //         return collect(new ActivityMenuBulKInsertResponse([]))->all();
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    // }

    /**

     * Update the status of an ActivityMenu record to 'rejected'.
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
     * Update the status of an ActivityMenu record to 'Active'.
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
     * Update the status of an ActivityMenu record to 'Approved'.
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
