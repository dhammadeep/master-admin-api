<?php

namespace App\Http\Modules\CaseValidation\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\CaseValidation\Enums\CaseStep;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Services\CaseListService;
use App\Http\Modules\CaseValidation\Services\GeoPlottingService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\CaseValidation\Requests\GeoPlottingApprovalRequest;

class GeoPlottingController extends Controller
{
    /**
     * Service instance for handling widget structure requests.
     *
     * @var GeoPlottingService $geoPlottingService
     */
    protected $caseListService, $geoPlottingService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param GeoPlottingService $geoPlottingService
     */
    public function __construct(CaseListService $caseListService,GeoPlottingService $geoPlottingService)
    {
        $this->caseListService = $caseListService;
        $this->geoPlottingService = $geoPlottingService;
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

        $completedValues = [
            CaseStep::LAND_KYC->value,
            CaseStep::WAREHOUSE_KYC->value,
            CaseStep::COMPANY_KYC->value
        ];

        // Define the enum values
        $caseSteps = [
            'pending' => CaseStep::GEO_PLOT->value,
            'completed' => $completedValues,
        ];

        $dto = new CaseListRequest($caseSteps);

        try {

            $data = $this->caseListService->getGeoPlotList($request, $dto);
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
     * Get the geo plotting form details for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoPlottingService->getGeoPlottingDetails(
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
     * Geo Plotting Reject for given caseId.
     *
     * @param GeoLocationApprovalRequest $geoLocationApprovalRequest
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoPlotReject(GeoPlottingApprovalRequest $geoPlottingApprovalRequest, int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoPlottingService->geoPlotReject(
                geoPlottingApprovalRequest: $geoPlottingApprovalRequest,
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
     * Geo Plotting Approval for given caseId.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoPlotApprove(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try {

            $data = $this->geoPlottingService->geoPlotApprove(
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
