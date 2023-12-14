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
use App\Http\Modules\CaseValidation\Services\LandDocService;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Requests\FarmFormRequest;
use App\Http\Modules\CaseValidation\Services\CaseListService;
use App\Http\Modules\CaseValidation\Responses\LandDocResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\LandDocWidgetRequest;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

class LandDocController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var LandDocService $landDocService
     *  @var DocWidgetService docWidgetService
     */
    protected $caseListService, $landDocService, $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param LandDocService $landDocService
     */
    public function __construct(CaseListService $caseListService,LandDocService $landDocService, DocWidgetService $docWidgetService)
    {
        $this->caseListService = $caseListService;
        $this->landDocService = $landDocService;
        $this->docWidgetService = $docWidgetService;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::LAND_KYC->value,
            'completed' => CaseStep::BASIC_KYC->value,
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
     * List page to display all owned farm list with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getOwnedList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::LAND_KYC->value,
            'completed' => CaseStep::BASIC_KYC->value,
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {
            $data = $this->caseListService->getOwnedList($request, $caseListOptions);
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
     * List page to display all leased farm list with pagination support.
     *
     *@method GET
     *
     * @return Response
     */
    public function getLeasedList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::LAND_KYC->value,
            'completed' => CaseStep::BASIC_KYC->value,
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getLeasedList($request, $caseListOptions);
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
     * Get the farm form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        $widgetId = DocWidget::LAND_DOC->value;

        try {

            $requestDetails = ['caseId' => $caseId, 'widgetId' => $widgetId];

            $validationErrors = LandDocWidgetRequest::validate($requestDetails);

            if ($validationErrors) {
                $data = $validationErrors;
                throw new Exception;
            }

            $formDetails = $this->landDocService->getFormDetails(
                caseId: $caseId
            );

            $widgetDocDetails = $this->docWidgetService->getWidgetStructure(
                caseId: $caseId,
                widgetId: $widgetId
            );

            $responseDto = new LandDocResponse();
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
     * store farm form details for given caseId.
     *
     * @param FarmFormRequest $farmFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDetails(FarmFormRequest $farmFormRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->landDocService->storeDetails(
                farmFormRequest: $farmFormRequest,
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
     * Land Reject Document for given caseId.
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

            $data = $this->landDocService->rejectDocument(
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
     * Land Approve Document for given caseId.
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

            $data = $this->landDocService->approveDocument(
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
