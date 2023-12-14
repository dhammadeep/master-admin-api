<?php

namespace App\Http\Modules\Gen\Services;

use Exception;
use Illuminate\Http\Request;
use App\Http\Modules\Gen\Requests\MenuRequest;
use App\Http\Modules\Gen\Repositories\MenuRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Gen\Responses\Lists\MenuListResponse;
use App\Http\Modules\Gen\Responses\Table\MenuTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\Gen\Responses\Table\MenuActivityTableCollection;


class MenuService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param MenuRepository $repository
     *
     * @return void
     */
    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return MenuTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
            $request->merge([
                'status' =>'APPROVED'
            ]);
           return new MenuTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return MenuTableCollection
     */
    public function getAllPaginatedMenuData($request)
    {
        // Return in the given API resource format
        try {
            $request->merge([
                'status' =>'APPROVED'
            ]);
            return new MenuActivityTableCollection($this->repository->findMenu($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return MenuTableCollection
     */
    public function getAllPaginatedMenuActivityData($request)
    {
        // Return in the given API resource format
        try {
            $request->merge([
                'status' =>'APPROVED'
            ]);
            $dashboard = collect([
                'mainMenu' => 'Dashboard',
                'href' => '/dashboard',
                'icon' => "<i class='fa-solid fa-chart-simple fa-lg'></i>",
                'subMenu' => [],
                'activity' => ["SUPER_ADMIN", "VALIDATE_CASE_KYC", "VALIDATE_CASE_FINANCE", "VALIDATE_CASE_WAREHOUSE", "VALIDATE_CASE_KML", "VALIDATE_CASE_SCHEDULING", "INCOMPLETE_CASE"]
            ]);
            return new MenuActivityTableCollection($this->repository->findMenuActivity($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Menu in the DB
     *
     * @param MenuRequest $data
     *
     * @return Array
     */
    public function add(MenuRequest $data )
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
     * Creates a new Menu in the DB
     *
     * @param MenuRequest $data
     *
     * @return Array
     */
    public function saveMenuOrder(Request $data )
    {
        try {
            return $this->repository->saveMenuOrder($data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }  catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Menu model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findMenuById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new MenuListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param MenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, int $id)
    {
        // Retrieve the Menu from the database
       try {
            $menu = $this->repository->findById($id);
            if ($menu) {
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
            return collect(new MenuBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Menu record to 'rejected'.
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
     * Update the status of an Menu record to 'Active'.
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
     * Update the status of an Menu record to 'Approved'.
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
