<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\CaseVisitRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseVisitDateRepository;
use App\Http\Modules\CaseValidation\Responses\ScheduledVisitDetailsResponse;
use App\Http\Modules\CaseValidation\Requests\CaseVisitDateReschedulingRequest;
use App\Http\Modules\CaseValidation\Repositories\ScheduledVisitDetailsRepository;


class ScheduledVisitDetailsService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var scheduledVisitDetailsRepository
     * @var CaseVisitDateRepository
     */
    protected $scheduledVisitDetailsRepository, $caseRepository, $caseVisitDateRepository, $caseVisitRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseGeoPlottingDetailsRepository $caseGeoPlottingDetailsRepository
     */
    public function __construct(CasesRepository $caseRepository, ScheduledVisitDetailsRepository $scheduledVisitDetailsRepository, CaseVisitDateRepository $caseVisitDateRepository, CaseVisitRepository $caseVisitRepository)
    {
        $this->scheduledVisitDetailsRepository = $scheduledVisitDetailsRepository;
        $this->caseVisitDateRepository = $caseVisitDateRepository;
        $this->caseVisitRepository = $caseVisitRepository;
        $this->caseRepository = $caseRepository;
    }

    /**
     * Get the scheduling visit details.
     *
     * @param int $caseId
     * @return array
     */
    public function getScheduledVisitDetails(int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            return new ScheduledVisitDetailsResponse($this->scheduledVisitDetailsRepository->getScheduledVisitDetails($caseId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * case visit rescheduling.
     *
     * @param CaseVisitDateReschedulingRequest $caseVisitDateReschedulingRequest
     * @param int $caseId
     * @return array
     */
    public function rescheduling(CaseVisitDateReschedulingRequest $caseVisitDateReschedulingRequest, int $caseId)
    {
        // $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case visit exists
            $caseVisitDetails = $this->caseVisitRepository->findByCaseIdCached($caseId);

            if (empty($caseVisitDetails)) {
                throw new Exception;
            }

            $caseVisitId = $caseVisitDetails->id;

            //update farm deatils
            return $this->caseVisitDateRepository->rescheduling($caseVisitDateReschedulingRequest, $caseVisitId);

            // TODO : Dispatch an event to check and update the case status
            // CaseStatusUpdateEvent::dispatch(
            //     $caseId,
            //     $status
            // );
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
