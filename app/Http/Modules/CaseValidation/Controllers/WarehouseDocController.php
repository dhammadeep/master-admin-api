<?php

namespace App\Http\Modules\CaseValidation\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\CaseValidation\Enums\CaseStep;
use App\Http\Modules\CaseValidation\Enums\DocWidget;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Services\CaseListService;
use App\Http\Modules\CaseValidation\Services\WarehouseDocService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\WarehouseFormRequest;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Responses\WarehouseDocResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\WarehouseDocWidgetRequest;
use App\Http\Modules\CaseValidation\Requests\WarehouseStackFormRequest;

class WarehouseDocController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var WarehouseDocService $warehouseDocService
     *  @var DocWidgetService docWidgetService
     */
    protected $caseListService, $warehouseDocService, $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param WarehouseDocService $warehouseDocService
     */
    public function __construct(CaseListService $caseListService, WarehouseDocService $warehouseDocService, DocWidgetService $docWidgetService)
    {
        $this->caseListService = $caseListService;
        $this->warehouseDocService = $warehouseDocService;
        $this->docWidgetService = $docWidgetService;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getWarehouseFarmerList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::WAREHOUSE_KYC->value,
            'completed' => CaseStep::BASIC_KYC->value,
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getWarehouseFarmerList($request, $caseListOptions);
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
     * List page to display all data with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getWarehouseCompanyList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::WAREHOUSE_KYC->value,
            'completed' => CaseStep::BASIC_KYC->value,
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getWarehouseCompanyList($request, $caseListOptions);
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
     * Get the warehouse form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        $widgetId = DocWidget::WAREHOUSE_DOC->value;

        try {

            $requestDetails = ['caseId' => $caseId, 'widgetId' => $widgetId];

            $validationErrors = WarehouseDocWidgetRequest::validate($requestDetails);

            if ($validationErrors) {
                $data = $validationErrors;
                throw new Exception;
            }

            $formDetails = $this->warehouseDocService->getFormDetails(
                caseId: $caseId
            );

            $stackDetails = $this->warehouseDocService->getStackDetails(
                caseId: $caseId
            );

            $widgetDocDetails = $this->docWidgetService->getWidgetStructure(
                caseId: $caseId,
                widgetId: $widgetId
            );

            $responseDto = new WarehouseDocResponse();
            $responseDto->caseId = $caseId;
            $responseDto->widgetId = $widgetId;
            $responseDto->form = $formDetails;
            $responseDto->stackDetails = $stackDetails;
            $responseDto->docWidget = $widgetDocDetails;

            $data = $responseDto;
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
     * store warehouse form details for given caseId.
     *
     * @param WarehouseFormRequest $WarehouseFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDetails(WarehouseFormRequest $warehouseFormRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->warehouseDocService->storeDetails(
                warehouseFormRequest: $warehouseFormRequest,
                caseId: $caseId
            );
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
     * store warehouse stack details for given caseId.
     *
     * @param WarehouseStackFormRequest $warehouseStackFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeStackDetails(WarehouseStackFormRequest $warehouseStackFormRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->warehouseDocService->storeStackDetails(
                warehouseStackFormRequest: $warehouseStackFormRequest,
                caseId: $caseId
            );
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
     * Warehouse Reject Document for given caseId.
     *
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->warehouseDocService->rejectDocument(
                rejectDocumentRequest: $rejectDocumentRequest,
                caseId: $caseId
            );
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
     * Warehouse Approve Document for given caseId.
     *
     * @param ApproveDocumentRequest $approveDocumentRequest
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->warehouseDocService->approveDocument(
                approveDocumentRequest: $approveDocumentRequest,
                caseId: $caseId
            );
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
