<?php

namespace App\Http\Modules\CaseValidation\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\CaseValidation\Enums\CaseStep;
use App\Http\Modules\CaseValidation\Services\GeoTagService;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Services\CaseListService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\GeoTagApprovalRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class GeoTagController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var GeoLocationService $geoLocationService
     */
    protected $caseListService, $geoTagService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param GeoTagService $geoLocationService
     */
    public function __construct(CaseListService $caseListService,GeoTagService $geoTagService)
    {
        $this->caseListService = $caseListService;
        $this->geoTagService = $geoTagService;
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

        $completedValues = [
            CaseStep::LAND_KYC->value,
            CaseStep::WAREHOUSE_KYC->value,
            CaseStep::COMPANY_KYC->value
        ];

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::GEO_TAG->value,
            'completed' => $completedValues,
        ];

        $dto = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getWarehouseFarmerList($request, $dto);
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
    public function getHarvestedList(Request $request)
    {
        $data = null;
        $code = "SUCCESS";

        $completedValues = [
            CaseStep::LAND_KYC->value,
            CaseStep::WAREHOUSE_KYC->value,
            CaseStep::COMPANY_KYC->value
        ];

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::GEO_TAG->value,
            'completed' => $completedValues,
        ];

        $caseListOptions = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getHarvestedList($request, $caseListOptions);
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

        $completedValues = [
            CaseStep::LAND_KYC->value,
            CaseStep::WAREHOUSE_KYC->value,
            CaseStep::COMPANY_KYC->value
        ];

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::GEO_TAG->value,
            'completed' => $completedValues,
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
     * Get the geo tag form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoTagService->getGeoTagDetails(
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
     * Geo tag reject for given caseId.
     *
     * @param GeoLocationApprovalRequest $geoLocationApprovalRequest
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoTagReject(GeoTagApprovalRequest $geoTagApprovalRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoTagService->geoTagReject(
                geoTagApprovalRequest: $geoTagApprovalRequest,
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
     * Geo tag approve for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoTagApprove(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoTagService->geoTagApprove(
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
