<?php

namespace App\Http\Modules\IncompleteCase\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\IncompleteCase\Services\IncompleteCaseService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class IncompleteCaseController extends Controller
{
    protected $service;

    /**
     * Injects CaseList Service dependency through the constructor
     *
     * @param IncompleteCaseService $service
     *
     * @return Void
     */
    public function __construct(IncompleteCaseService $service)
    {
        $this->service = $service;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@link http://test/example
     *
     *@method GET
     *
     * @return Response
     */
    public function getList()
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->getAllPaginatedTableData();
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

        if ($code !== "SUCCESS") {
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
     * List page to display all pending incomplete data with pagination support.
     *
     *@link http://test/example
     *
     *@method GET
     *
     * @return Response
     */
    public function getPendingList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->getPendingList($request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "VALIDATION_ERROR";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

        if ($code !== "SUCCESS") {
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
     * List page to display all unpaid incomplete data with pagination support.
     *
     *@link http://test/example
     *
     *@method GET
     *
     * @return Response
     */
    public function getUnpaidList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        try {
            $data = $this->service->getUnpaidList($request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "VALIDATION_ERROR";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

        if ($code !== "SUCCESS") {
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
}
