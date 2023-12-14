<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Repositories\GeoPlotRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoPlotRepository;
use App\Http\Modules\CaseValidation\Requests\GeoPlottingApprovalRequest;
use App\Http\Modules\CaseValidation\Responses\CaseGeoPlottingDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoPlottingDetailsRepository;


class GeoPlottingService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseGeoPlottingDetailsRepository
     */
    protected $caseGeoPlottingDetailsRepository, $caseRepository, $geoPlotRepository, $caseGeoPlotRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseGeoPlottingDetailsRepository $caseGeoPlottingDetailsRepository
     */
    public function __construct(CasesRepository $caseRepository, caseGeoPlottingDetailsRepository $caseGeoPlottingDetailsRepository, GeoPlotRepository $geoPlotRepository, CaseGeoPlotRepository $caseGeoPlotRepository)
    {
        $this->caseGeoPlottingDetailsRepository = $caseGeoPlottingDetailsRepository;
        $this->geoPlotRepository = $geoPlotRepository;
        $this->caseGeoPlotRepository = $caseGeoPlotRepository;
        $this->caseRepository = $caseRepository;
    }


    /**
     * Get the geo plotting details.
     *
     * @param int $caseId
     * @return array
     */
    public function getGeoPlottingDetails(int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }
            return new CaseGeoPlottingDetailsResponse($this->caseGeoPlottingDetailsRepository->getGeoPlottingDetails($caseId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * geo plotting rejection.
     *
     * @param GeoPlottingApprovalRequest $geoPlottingApprovalRequest
     * @param int $caseId
     * @return array
     */
    public function geoPlotReject(GeoPlottingApprovalRequest $geoPlottingApprovalRequest, $caseId)
    {
        // $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case geo plot exists
            $caseGeoDetails = $this->caseGeoPlotRepository->findByCaseIdCached($caseId);

            if (empty($caseGeoDetails)) {
                throw new Exception;
            }

            $geoPlotId = $caseGeoDetails->geo_plot_id;

            // Check if geo plot id exist or not
            $geoPlotDetails = $this->geoPlotRepository->findByIdCached($geoPlotId);

            if (empty($geoPlotDetails)) {
                throw new Exception;
            }

            //update geo plotting status
            $this->geoPlotRepository->geoPlotReject($geoPlottingApprovalRequest, $geoPlotId);

            //If status is VALIDATION_FAILED then only update case status to VALIDATION_FAILED
            $this->caseRepository->rejectCase($geoPlottingApprovalRequest->rejectionReasonId, $caseId);

            // TODO : Dispatch an event to check and update the case status
            // CaseStatusUpdateEvent::dispatch(
            //     $caseId,
            //     $status
            // );
            return true;
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * geo plotting approve.
     *
     * @param int $caseId
     * @return array
     */
    public function geoPlotApprove($caseId)
    {
        $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case geo plot exists
            $caseGeoDetails = $this->caseGeoPlotRepository->findByCaseIdCached($caseId);

            if (empty($caseGeoDetails)) {
                throw new Exception;
            }

            $geoPlotId = $caseGeoDetails->geo_plot_id;

            // Check if geo plot id exist or not
            $geoPlotDetails = $this->geoPlotRepository->findByIdCached($geoPlotId);

            if (empty($geoPlotDetails)) {
                throw new Exception;
            }

            //update geo plotting status
            $this->geoPlotRepository->geoPlotApprove($geoPlotId);

            // TODO : Dispatch an event to check and update the case status
            CaseStatusUpdateEvent::dispatch(
                $caseId,
                $status
            );

            return Null;

        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
