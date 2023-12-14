<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use Illuminate\Http\Request;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use App\Http\Modules\CaseValidation\Requests\CompanyFormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\DrkDocRepository;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Repositories\CompanyRepository;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Repositories\LocationRepository;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Responses\CompanyDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\CompanyDocRepository;
use App\Http\Modules\CaseValidation\Repositories\UserCompanyRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseUserCompanyDetailsRepository;


class CompanyDocService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseUserCompanyDetailsRepository
     */
    protected $caseUserCompanyDetailsRepository, $companyRepository, $caseRepository, $userCompanyRepository, $locationRepository, $companyDocRepository, $drkDocRepository, $rejectionReasonRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseUserCompanyDetailsRepository $caseUserCompanyDetailsRepository
     */
    public function __construct(CaseUserCompanyDetailsRepository $caseUserCompanyDetailsRepository, CompanyRepository $companyRepository, UserCompanyRepository $userCompanyRepository, CasesRepository $caseRepository, LocationRepository $locationRepository, CompanyDocRepository $companyDocRepository, DrkDocRepository $drkDocRepository, RejectionReasonRepository $rejectionReasonRepository)
    {
        $this->caseUserCompanyDetailsRepository = $caseUserCompanyDetailsRepository;
        $this->caseRepository = $caseRepository;
        $this->companyRepository = $companyRepository;
        $this->userCompanyRepository = $userCompanyRepository;
        $this->locationRepository = $locationRepository;
        $this->companyDocRepository = $companyDocRepository;
        $this->drkDocRepository = $drkDocRepository;
        $this->rejectionReasonRepository = $rejectionReasonRepository;
    }


    /**
     * Get the Company form details.
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

            return new CompanyDetailsResponse($this->caseUserCompanyDetailsRepository->getCompanyFormDetails($userId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store Company form details.
     *
     * @param CompanyFormRequest $companyFormRequest
     * @return array
     */
    public function storeDetails(CompanyFormRequest $companyFormRequest, int $caseId)
    {
        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            $userId = $caseDetails->user_id;
            // Check if the case Company exists
            $userCompanyDetails = $this->userCompanyRepository->findByUserIdCached($userId);

            if (empty($userCompanyDetails)) {
                throw new Exception;
            }

            $companyId = $userCompanyDetails->company_id;

            // Check if Company id exist or not
            $companyDetails = $this->companyRepository->findByIdCached($companyId);

            if (empty($companyDetails)) {
                throw new Exception;
            }

            $locationId = $companyDetails->location_id;

            if (!empty($locationId)) {
                //check company_id and location_id duplication
                $locationDetails = $this->companyRepository->checkCompanyLocationExistOrNot($companyId, $locationId);


                if ($locationDetails) {
                    //Location id is already in use
                    throw new Exception;
                }
            }

            if (is_null($locationId)) {
                $locationId = 0;
            }

            $locationRequest = collect($companyFormRequest);

            //create or update location
            $locationId = $this->locationRepository->insertUpdateLocation($locationRequest, $locationId);

            $companyFormRequest->merge(['locationId' => $locationId]);

            //update Company deatils
            return $this->companyRepository->updateCompanyDetails($companyFormRequest, $companyId);

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
     * Company reject document.
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

            // Check if the user company exists
            $userCompanyDetails = $this->userCompanyRepository->findByUserIdCached($userId);

            if (empty($userCompanyDetails)) {
                throw new Exception;
            }

            $companyId = $userCompanyDetails->company_id;

            // Check if the case company Doc exists
            $companyDocDetails = $this->companyDocRepository->findByCompanyIdAndDocIdCached($companyId, $rejectDocumentRequest->docId);

            if (empty($companyDocDetails)) {
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

            //update company with company doc status
            $this->companyRepository->rejectDocument($rejectDocumentRequest, $companyId);

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
     * Warehouse approve document.
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

            // Check if the user company exists
            $userCompanyDetails = $this->userCompanyRepository->findByUserIdCached($userId);

            if (empty($userCompanyDetails)) {
                throw new Exception;
            }

            $companyId = $userCompanyDetails->company_id;

            // Check if the case company Doc exists
            $companyDocDetails = $this->companyDocRepository->findByCompanyIdAndDocIdCached($companyId, $approveDocumentRequest->docId);
            // dd($companyDocDetails);

            if (empty($companyDocDetails)) {
                throw new Exception;
            }
            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($approveDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->approveDocument($approveDocumentRequest);

            //update company with company doc status
            $this->companyRepository->approveDocument($approveDocumentRequest, $companyId);

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
