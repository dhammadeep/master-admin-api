<?php

namespace App\Http\Masters\Menu\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Menu\Services\ActivityMenuService;
use App\Http\Masters\Menu\Requests\ActivityMenuRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ActivityMenuController extends Controller
{
    protected $service;

    /**
     * Base URL of this module
    */
    private string $baseUrl = "";

    /**
     * Injects ActivityMenu Service dependency through the constructor
     *
     * @param ActivityMenuService $service
     *
     * @return Void
     */
    public function __construct(ActivityMenuService $service)
    {
        $this->service = $service;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@link http://api/v1/menu/activity-menu
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllPaginatedTableData($request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * Displays a form to add new ActivityMenu
     *
     *@link http://api/v1/menu/activity-menu/create/
     *
     *@method GET
     *
     * @return Response
     */
    public function create()
    {
        $data = null;
        $code = "SUCCESS";
        try{
             $data = [
                'formData' => [
                    //'type' => 'Add',
                    //'title' => 'Add Activity Menu',
                    'fields' => $this->service->getFormFields(),
                    'formStoreUrl' => $this->baseUrl . 'menu/activity-menu',
                ],
                'bulkInsert' => []
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

         if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
              return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

     /**
     * Store a newly created resource in storage.
     *
     *@link http://api/v1/menu/activity-menu/store
     *
     *@method POST
     *
     * @param ActivityMenuRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ActivityMenuRequest $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->add(data: $request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
              return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

     /**
     * Displays a form with values to update ActivityMenu
     *
     *@link http://api/v1/menu/activity-menu/edit/id
     *
     *@method GET
     *
     * @return Response
     */
    public function edit(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $tableData = $this->service->findActivityMenuById($id);
            $data = collect($this->service->getFormFields())->map(function ($fields) use ($tableData) {
                $attrib = $fields['name'];
                $fields['value']  = $tableData[$attrib];
                return $fields;
            });

            $data =  [
                'formData' => [
                // 'type' => 'Edit',
                // 'methodName' => 'PUT',
                // 'title' => 'Activity Menu',
                   'fields' => $data,
                   'formStoreUrl' => "{$this->baseUrl}menu/activity-menu/$id",
                ]
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            dd($e);
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
             return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }
    }

    /**
     * update the status in list
     *
     *@link http://api/v1/menu/activity-menu/update/id
     *
     *@method PATCH/PUT
     *
     * @param ActivityMenuRequest  $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function update(ActivityMenuRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->update($request, $id);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (ModelNotFoundException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }else{
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }
    }

    /**
     * reject the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
     public function reject(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateRejectStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }else{
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * finalize the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function finalize(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateFinalizeStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }else{
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * approve the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function approve(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateApproveStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }else{
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }
}
