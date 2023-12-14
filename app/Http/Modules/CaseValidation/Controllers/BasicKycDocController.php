<?php

namespace App\Http\Modules\CaseValidation\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\CaseValidation\Enums\CaseStep;
use App\Http\Modules\CaseValidation\Enums\DocWidget;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Services\CaseListService;
use App\Http\Modules\CaseValidation\Services\BasicKycDocService;
use App\Http\Modules\CaseValidation\Requests\BasicKycFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Responses\BasicKycDocResponse;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\BasicKycDocWidgetRequest;

class BasicKycDocController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     * @var CaseListService $caseListService
     * @var BasicKycDocService $basicKycDocService
     *  @var DocWidgetService docWidgetService
     */
    protected $caseListService, $basicKycDocService, $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param BasicKycDocService $BasicKycDocService
     */
    public function __construct(CaseListService $caseListService, BasicKycDocService $basicKycDocService, DocWidgetService $docWidgetService)
    {
        $this->caseListService = $caseListService;
        $this->basicKycDocService = $basicKycDocService;
        $this->docWidgetService = $docWidgetService;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getFarmList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::BASIC_KYC->value,
            'completed' => '',
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {
            $data = $this->caseListService->getFarmList($request, $caseListOptions);
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
    public function getWarehouseFarmerList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::BASIC_KYC->value,
            'completed' => '',
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
            'pending' => CaseStep::BASIC_KYC->value,
            'completed' => '',
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
     * Get the basic KYC form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        //get widget id
        $widgetId = DocWidget::BASIC_KYC_DOC->value;


        try {

            $requestDetails = ['caseId' => $caseId, 'widgetId' => $widgetId];

            //validate caseId and widgetId using request DTO
            $validationErrors = BasicKycDocWidgetRequest::validate($requestDetails);

            //if request dto failed throw exception
            if ($validationErrors) {
                $data = $validationErrors;
                throw new Exception;
            }

            $formDetails = $this->basicKycDocService->getFormDetails(
                caseId: $caseId
            );

            //to get basic KYC doc widget structure
            $widgetDocDetails = $this->docWidgetService->getWidgetStructure(
                caseId: $caseId,
                widgetId: $widgetId
            );

            //generate final response
            $responseDto = new BasicKycDocResponse();
            $responseDto->caseId = $caseId;
            $responseDto->widgetId = $widgetId;
            $responseDto->form = $formDetails;
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
     * store basic KYC form details for given caseId.
     *
     * @param BasicKycFormRequest $BasicKycFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDetails(BasicKycFormRequest $basicKycFormRequest, int $caseId)
    {
        $data = $message = null;
        $code = "SUCCESS";

        try {

            $data = $this->basicKycDocService->storeDetails(
                basicKycFormRequest: $basicKycFormRequest,
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
            $message = $e->getMessage();
        }

        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
                data: $data,
                message: $message
            );
        } else {
            return ResponseHelper::respond(
                code: $code,
                data: $data,
                message: $message
            );
        }
    }

    /**
     * User Basic KYC Reject Document for given caseId.
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

            $data = $this->basicKycDocService->rejectDocument(
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
     * User basic KYC Approve Document for given caseId.
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
            $data = $this->basicKycDocService->approveDocument(
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
