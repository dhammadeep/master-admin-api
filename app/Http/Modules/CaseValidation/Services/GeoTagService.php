<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use Illuminate\Http\Request;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\GeoTagRepository;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\GeoTagApprovalRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoTagRepository;
use App\Http\Modules\CaseValidation\Responses\CaseGeoTagDetailsResponse;
use App\Http\Modules\CaseValidation\Responses\Table\CaseListTableCollection;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoTagDetailsRepository;


class GeoTagService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseGeoTagDetailsRepository
     */
    protected $caseGeoTagDetailsRepository, $caseRepository, $geoTagRepository, $caseGeoTagRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseGeoTagDetailsRepository $caseGeoTagDetailsRepository
     */
    public function __construct(CasesRepository $caseRepository, CaseGeoTagDetailsRepository $caseGeoTagDetailsRepository, GeoTagRepository $geoTagRepository, CaseGeoTagRepository $caseGeoTagRepository)
    {
        $this->caseGeoTagDetailsRepository = $caseGeoTagDetailsRepository;
        $this->geoTagRepository = $geoTagRepository;
        $this->caseGeoTagRepository = $caseGeoTagRepository;
        $this->caseRepository = $caseRepository;
    }

    /**
     * Get the geo tag details.
     *
     * @param int $caseId
     * @return array
     */
    public function getGeoTagDetails(int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            return new CaseGeoTagDetailsResponse($this->caseGeoTagDetailsRepository->getGeoTagDetails($caseId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * geo Tag reject.
     *
     * @param GeoTagApprovalRequest $geoTagApprovalRequest
     * @param int $caseId
     * @return array
     */
    public function geoTagReject(GeoTagApprovalRequest $geoTagApprovalRequest, $caseId)
    {
        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case geo tag exists
            $caseGeoDetails = $this->caseGeoTagRepository->findByCaseIdCached($caseId);

            if (empty($caseGeoDetails)) {
                throw new Exception;
            }

            $geoTagId = $caseGeoDetails->geo_tag_id;

            // Check if geo Tag id exist or not
            $geoTagDetails = $this->geoTagRepository->findByIdCached($geoTagId);

            if (empty($geoTagDetails)) {
                throw new Exception;
            }

            //update geo tag status
            $this->geoTagRepository->geoTagReject($geoTagApprovalRequest, $geoTagId);

            //If sttaus is VALIDATION_FAILED then only update case status to VALIDATION_FAILED
            $this->caseRepository->rejectCase($geoTagApprovalRequest->rejectionReasonId, $caseId);

            // TODO : Dispatch an event to check and update the case status
            // Event::dispatch(new CaseStatusUpdateEvent($data['caseId']));

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
     * geo Tag approve.
     *
     * @param int $caseId
     * @return array
     */
    public function geoTagApprove($caseId)
    {
        $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case geo tag exists
            $caseGeoDetails = $this->caseGeoTagRepository->findByCaseIdCached($caseId);

            if (empty($caseGeoDetails)) {
                throw new Exception;
            }

            $geoTagId = $caseGeoDetails->geo_tag_id;

            // Check if geo Tag id exist or not
            $geoTagDetails = $this->geoTagRepository->findByIdCached($geoTagId);

            if (empty($geoTagDetails)) {
                throw new Exception;
            }

            //update geo tag status
            $this->geoTagRepository->geoTagApprove($geoTagId);

             // Dispatch an event to check and update the case status
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
