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
use App\Http\Modules\CaseValidation\Requests\BankBranchRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Services\BankAccountDocService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\BankAccountFormRequest;
use App\Http\Modules\CaseValidation\Responses\BankAccountDocResponse;
use App\Http\Modules\CaseValidation\Requests\UserBankAccountFormRequest;
use App\Http\Modules\CaseValidation\Requests\BankAccountDocWidgetRequest;

class BankAccountDocController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var BankAccountDocService $bankAccountDocService
     *  @var DocWidgetService docWidgetService
     */
    protected $caseListService, $bankAccountDocService, $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param BankAccountDocService $bankAccountDocService
     */
    public function __construct(CaseListService $caseListService,BankAccountDocService $bankAccountDocService, DocWidgetService $docWidgetService)
    {
        $this->caseListService = $caseListService;
        $this->bankAccountDocService = $bankAccountDocService;
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
            'pending' => CaseStep::BANK_ACCOUNT_KYC->value,
            'completed' => CaseStep::LAND_KYC->value,
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
            'pending' => CaseStep::BANK_ACCOUNT_KYC->value,
            'completed' => CaseStep::WAREHOUSE_KYC->value,
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
            'pending' => CaseStep::BANK_ACCOUNT_KYC->value,
            'completed' => CaseStep::COMPANY_KYC->value,
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
     * Get the bank account form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        $widgetId = DocWidget::BANK_ACCOUNT_DOC->value;

        try {

            $requestDetails = ['caseId' => $caseId, 'widgetId' => $widgetId];

            $validationErrors = BankAccountDocWidgetRequest::validate($requestDetails);

            if ($validationErrors) {
                $data = $validationErrors;
                throw new Exception;
            }

            $formDetails = $this->bankAccountDocService->getFormDetails(
                caseId: $caseId
            );

            $widgetDocDetails = $this->docWidgetService->getWidgetStructure(
                caseId: $caseId,
                widgetId: $widgetId
            );

            $responseDto = new BankAccountDocResponse();
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
     * store bank account form details for given caseId.
     *
     * @param UserBankAccountFormRequest $userBankAccountFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDetails(UserBankAccountFormRequest $userBankAccountFormRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->bankAccountDocService->storeDetails(
                userBankAccountFormRequest: $userBankAccountFormRequest,
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
     * get bank branch details by ifsc code.
     *
     * @param BankAccountFormRequest $userBankAccountFormRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankBranchDetailsByIfsc(BankBranchRequest $bankBranchRequest)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->bankAccountDocService->getBankBranchDetailsByIfsc(
                bankBranchRequest: $bankBranchRequest
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
     * User Bank Account Reject Document for given caseId.
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

            $data = $this->bankAccountDocService->rejectDocument(
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
     * User Bank Account Approve Document for given caseId.
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

            $data = $this->bankAccountDocService->approveDocument(
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
