<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use Illuminate\Http\Request;
use App\Events\CaseStatusUpdateEvent;
use App\Exceptions\CaseValidationException;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use App\Http\Modules\Authentication\Repositories\UserRepository;
use App\Http\Modules\CaseValidation\Requests\BasicKycFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\DrkDocRepository;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Repositories\UserDocRepository;
use App\Http\Modules\CaseValidation\Repositories\UserKycRepository;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\LocationRepository;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Responses\BasicKycDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\BasicKycDetailsRepository;

class BasicKycDocService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var BasicKycDetailsRepository
     */
    protected $basicKycDetailsRepository, $userKycRepository, $caseRepository, $locationRepository, $userRepository, $drkDocRepository, $userDocRepository, $rejectionReasonRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param BasicKycDetailsRepository $basicKycDetailsRepository
     */
    public function __construct(BasicKycDetailsRepository $basicKycDetailsRepository, UserKycRepository $userKycRepository, CasesRepository $caseRepository, LocationRepository $locationRepository, UserRepository $userRepository, DrkDocRepository $drkDocRepository, UserDocRepository $userDocRepository, RejectionReasonRepository $rejectionReasonRepository)
    {
        $this->basicKycDetailsRepository = $basicKycDetailsRepository;
        $this->caseRepository = $caseRepository;
        $this->userKycRepository = $userKycRepository;
        $this->locationRepository = $locationRepository;
        $this->userRepository = $userRepository;
        $this->drkDocRepository = $drkDocRepository;
        $this->userDocRepository = $userDocRepository;
        $this->rejectionReasonRepository = $rejectionReasonRepository;
    }


    /**
     * Get the basic kyc form details.
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

            $userId = $caseDetails->user_id;

            return new BasicKycDetailsResponse($this->basicKycDetailsRepository->getBasicKycFormDetails($userId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store basic kyc form details.
     *
     * @param BasicKycFormRequest $basicKycFormRequest
     * @return array
     */
    public function storeDetails(BasicKycFormRequest $basicKycFormRequest, int $caseId)
    {
        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            $userId = $caseDetails->user_id;

            // Check if user basic KYC exists
            $userKycDetails = $this->userKycRepository->findByUserIdCached($userId);


            $locationId = 0;
            if (!empty($userKycDetails)) {
                $locationId = $userKycDetails->location_id;

                if (!empty($locationId)) {

                    //check user_id and location_id duplication
                    $locationDetails = $this->userKycRepository->checkUserLocationExistOrNot($userId, $locationId);

                    if ($locationDetails) {
                        //Location id is already in use
                        throw new Exception;
                    }
                }
            }

            if (is_null($locationId)) {
                $locationId = 0;
            }
            $locationRequest = collect($basicKycFormRequest);

            //create or update location
            $locationId = $this->locationRepository->insertUpdateLocation($locationRequest, $locationId);

            $basicKycFormRequest->merge(['locationId' => $locationId]);

            //create or update user kyc
            return $this->userKycRepository->insertUpdateUserKyc($basicKycFormRequest, $userId);

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
     * Basic KYC reject document.
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

            $userId = $caseDetails->user_id;

            // Check if the case user exists
            $userDetails = $this->userRepository->findByIdCached($userId);

            if (empty($userDetails)) {
                throw new Exception;
            }

            // Check if the case User Doc exists
            $userDocDetails = $this->userDocRepository->findByUserIdAndDocIdCached($userId, $rejectDocumentRequest->docId);

            if (empty($userDocDetails)) {
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

            //update user with user doc status
            $this->userRepository->rejectDocument($rejectDocumentRequest, $userId);

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
     * Basic KYC approve document.
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

            $userId = $caseDetails->user_id;

            // Check if the case user exists
            $userDetails = $this->userRepository->findByIdCached($userId);

            if (empty($userDetails)) {
                throw new Exception;
            }

            // Check if the case User Doc exists
            $userDocDetails = $this->userDocRepository->findByUserIdAndDocIdCached($userId, $approveDocumentRequest->docId);

            if (empty($userDocDetails)) {
                throw new Exception;
            }

            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($approveDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->approveDocument($approveDocumentRequest);

            //update user with user doc status
            $this->userRepository->approveDocument($approveDocumentRequest, $userId);



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
