<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Requests\FarmFormRequest;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use App\Http\Modules\CaseValidation\Repositories\FarmRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\DrkDocRepository;
use App\Http\Modules\CaseValidation\Responses\FarmDetailsResponse;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Repositories\FarmDocRepository;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\CaseFarmRepository;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\CaseFarmDetailsRepository;


class LandDocService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseFarmDetailsRepository
     */
    protected $caseFarmDetailsRepository, $farmRepository, $farmDocRepository, $drkDocRepository, $caseRepository, $caseFarmRepository, $rejectionReasonRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseFarmDetailsRepository $caseFarmDetailsRepository
     */
    public function __construct(CaseFarmDetailsRepository $caseFarmDetailsRepository, FarmRepository $farmRepository, FarmDocRepository $farmDocRepository, DrkDocRepository $drkDocRepository, CaseFarmRepository $caseFarmRepository, CasesRepository $caseRepository, RejectionReasonRepository $rejectionReasonRepository)
    {
        $this->caseFarmDetailsRepository = $caseFarmDetailsRepository;
        $this->caseRepository = $caseRepository;
        $this->farmRepository = $farmRepository;
        $this->caseFarmRepository = $caseFarmRepository;
        $this->rejectionReasonRepository = $rejectionReasonRepository;
        $this->drkDocRepository = $drkDocRepository;
        $this->farmDocRepository = $farmDocRepository;
    }

    /**
     * Get the farm form details.
     *
     * @param int $caseId
     * @return array
     */
    public function getFormDetails(int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            return new FarmDetailsResponse($this->caseFarmDetailsRepository->getLandDocFormDetails($caseId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store farm form details.
     *
     * @param FarmFormRequest $farmFormRequest
     * @return array
     */
    public function storeDetails(FarmFormRequest $farmFormRequest, int $caseId)
    {
        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case farm exists
            $caseFarmDetails = $this->caseFarmRepository->findByCaseIdCached($caseId);

            if (empty($caseFarmDetails)) {
                throw new Exception;
            }

            $farmId = $caseFarmDetails->farm_id;

            // Check if farm id exist or not
            $farmDetails = $this->farmRepository->findByIdCached($farmId);

            if (empty($farmDetails)) {
                throw new Exception;
            }

            //update farm deatils
            return $this->farmRepository->updateFarmDetails($farmFormRequest, $farmId);

            // TODO : Dispatch an event to check and update the case status
            // Event::dispatch(new CaseStatusUpdateEvent($data['caseId']));


        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Land Doc reject document.
     *
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @param int $caseId
     * @return array
     */
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest, $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case farm exists
            $caseFarmDetails = $this->caseFarmRepository->findByCaseId($caseId);

            if (empty($caseFarmDetails)) {
                throw new Exception;
            }

            $farmId = $caseFarmDetails->farm_id;

            // Check if the case Farm Doc exists
            $farmDocDetails = $this->farmDocRepository->findByFarmIdAndDocIdCached($farmId, $rejectDocumentRequest->docId);

            if (empty($farmDocDetails)) {
                throw new Exception;
            }

            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($rejectDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            // rejection id exist
            $rejectionReason = $this->rejectionReasonRepository->findByIdCached($rejectDocumentRequest->rejectionReasonId);
            if (empty($rejectionReason)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->rejectDocument($rejectDocumentRequest);

            //update farm with farm doc status
            $this->farmRepository->rejectDocument($rejectDocumentRequest, $farmId);

            //If sttaus is VALIDATION_FAILED then only update case status to VALIDATION_FAILED
            $this->caseRepository->rejectCase($rejectDocumentRequest->rejectionReasonId, $caseId);

            // TODO : Dispatch an event to check and update the case status
            // Event::dispatch(new CaseStatusUpdateEvent($data['caseId']));

            return Null;
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Farm approve document.
     *
     * @param ApproveDocumentRequest $approveDocumentRequest
     * @param int $caseId
     * @return array
     */
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest, $caseId)
    {
        $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case farm exists
            $caseFarmDetails = $this->caseFarmRepository->findByCaseId($caseId);

            if (empty($caseFarmDetails)) {
                throw new Exception;
            }

            $farmId = $caseFarmDetails->farm_id;

            // Check if the case farm doc exists
            $farmDocDetails = $this->farmDocRepository->findByFarmIdAndDocIdCached($farmId, $approveDocumentRequest->docId);

            if (empty($farmDocDetails)) {
                throw new Exception;
            }

            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($approveDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->approveDocument($approveDocumentRequest);

            //update farm with farm doc status
            $this->farmRepository->approveDocument($approveDocumentRequest, $farmId);

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
