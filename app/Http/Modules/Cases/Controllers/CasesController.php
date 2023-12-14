<?php

namespace App\Http\Modules\Cases\Controllers;

use Exception;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\Cases\Services\CasesService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CasesController extends Controller
{
   /**
     * Service instance for handling widget structure requests.
     *
     * @var CasesService
     */
    protected $casesService;

    /**
     * Initialize the controller with the specified service.
     *
     * @param CasesService $CasesService
     */
    public function __construct(CasesService $casesService)
    {
        $this->casesService = $casesService;
    }

    /**
     * Get the case brief details.
     *
     * @param int $caseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBriefDetails(int $caseId)
    {
        $data = null;
        $code = "SUCCESS";

        try{

            $data = $this->casesService->getBriefDetails(
                caseId:$caseId
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
