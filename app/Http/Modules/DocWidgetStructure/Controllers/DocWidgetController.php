<?php

namespace App\Http\Modules\DocWidgetStructure\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\DocWidgetStructure\Requests\DocWidgetRequest;
use App\Http\Modules\DocWidgetStructure\Services\DocWidgetService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DocWidgetController extends Controller
{
   /**
     * Service instance for handling widget structure requests.
     *
     * @var DocWidgetService
     */
    protected $docWidgetService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param DocWidgetService $docWidgetService
     */
    public function __construct(DocWidgetService $docWidgetService)
    {
        $this->docWidgetService = $docWidgetService;
    }

    /**
     * Get the widget structure and document details based on case ID and widget ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWidgetStructure(DocWidgetRequest $docWidgetrequest)
    {
        $data = null;
        $code = "SUCCESS";

        try{

            $data = $this->docWidgetService->getWidgetStructure(
                caseId:$docWidgetrequest->caseId,
                widgetId:$docWidgetrequest->widgetId
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
