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
use App\Http\Modules\CaseValidation\Services\CompanyDocService;
use App\Http\Modules\CaseValidation\Requests\CompanyFormRequest;
use App\Http\Modules\CaseValidation\Responses\CompanyDocResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\CompanyDocWidgetRequest;

class CompanyDocController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var CompanyDocService $companyDocService
     *  @var DocWidgetService docWidgetService
     */
    protected $caseListService, $companyDocService, $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param CompanyDocService $companyDocService
     */
    public function __construct(CaseListService $caseListService,CompanyDocService $companyDocService, DocWidgetService $docWidgetService)
    {
        $this->caseListService = $caseListService;
        $this->companyDocService = $companyDocService;
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
            'pending' => CaseStep::COMPANY_KYC->value,
            'completed' => CaseStep::WAREHOUSE_KYC->value,
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
     * Get the Company form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        $widgetId = DocWidget::COMPANY_DOC->value;

        try {

            $requestDetails = ['caseId' => $caseId, 'widgetId' => $widgetId];

            $validationErrors = CompanyDocWidgetRequest::validate($requestDetails);

            if ($validationErrors) {
                $data = $validationErrors;
                throw new Exception;
            }

            $formDetails = $this->companyDocService->getFormDetails(
                caseId: $caseId
            );

            $widgetDocDetails = $this->docWidgetService->getWidgetStructure(
                caseId: $caseId,
                widgetId: $widgetId
            );

            $responseDto = new CompanyDocResponse();
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
     * store company form details for given caseId.
     *
     * @param CompanyFormRequest $companyFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDetails(CompanyFormRequest $companyFormRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->companyDocService->storeDetails(
                companyFormRequest: $companyFormRequest,
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
     * Company Reject Document for given caseId.
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

            $data = $this->companyDocService->rejectDocument(
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
     * Company Approve Document for given caseId.
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

            $data = $this->companyDocService->approveDocument(
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
