<?php

namespace App\Http\Masters\Menu\Services;

use Exception;
use App\Http\Masters\Menu\Requests\SubMenuRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Menu\Repositories\SubMenuRepository;
use App\Http\Masters\Menu\Responses\menuDropdownResponse;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use App\Http\Masters\Menu\Responses\Lists\SubMenuListResponse;
use App\Http\Masters\Menu\Responses\Table\SubMenuTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class SubMenuService
{
    protected $repository, $dropdownRepository;

    /**
     * Constructor based dependency injection
     *
     * @param SubMenuRepository $repository
     *
     * @return void
     */
    public function __construct(SubMenuRepository $repository,DropdownRepository $dropdownRepository)
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
     * @return SubMenuTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $menuDropdownResponse = new MenuDropdownResponse();
            $menuDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findMenu());
            $menuDropdownResponse = $menuDropdownResponse->formFieldAtributes;
           return new SubMenuTableCollection($this->repository->find($request),$menuDropdownResponse);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new SubMenu in the DB
     *
     * @param SubMenuRequest $data
     *
     * @return Array
     */
    public function add(SubMenuRequest $data )
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
     * Render the edit view for the SubMenu model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findSubMenuById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new SubMenuListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param SubMenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SubMenuRequest $request, int $id)
    {
        // Retrieve the SubMenu from the database
       try {
            $subMenu = $this->repository->findById($id);
            if ($subMenu) {
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
            $warehouseDropdownResponse = new MenuDropdownResponse();
            $warehouseDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findMenu());
            $warehouseDropdownResponse = $warehouseDropdownResponse->formFieldAtributes;
            $final = array_merge([$warehouseDropdownResponse],$formResponse);
            return $final;
            // return $this->repository->getFormFields();
        } catch (Exception $e) {
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
    //         return collect(new SubMenuBulKInsertResponse([]))->all();
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    // }

    /**

     * Update the status of an SubMenu record to 'rejected'.
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
     * Update the status of an SubMenu record to 'Active'.
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
     * Update the status of an SubMenu record to 'Approved'.
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
