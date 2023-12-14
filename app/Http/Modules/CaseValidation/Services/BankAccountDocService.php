<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;

use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Requests\BankBranchRequest;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\DrkDocRepository;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\BankBranchRepository;
use App\Http\Modules\CaseValidation\Requests\UserBankAccountFormRequest;
use App\Http\Modules\CaseValidation\Responses\BankBranchDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\UserBankAccountRepository;
use App\Http\Modules\CaseValidation\Repositories\UserBankAccountDocRepository;
use App\Http\Modules\CaseValidation\Responses\CaseUserBankAccountDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\CaseUserBankAccountDetailsRepository;


class BankAccountDocService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseUserBankAccountDetailsRepository
     */
    protected $caseUserBankAccountDetailsRepository, $userBankAccountRepository, $caseRepository, $bankBranchRepository, $drkDocRepository, $userBankAccountDocRepository, $rejectionReasonRepository, $caseListInitialVisitFarmerRepository, $caseListInitialVisitWarehouseWithoutCompanyRepository, $caseListInitialVisitWarehouseWithCompanyRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseUserBankAccountDetailsRepository $caseUserBankAccountDetailsRepository
     */
    public function __construct(CaseUserBankAccountDetailsRepository $caseUserBankAccountDetailsRepository, UserBankAccountRepository $userBankAccountRepository, CasesRepository $caseRepository, BankBranchRepository $bankBranchRepository, DrkDocRepository $drkDocRepository, UserBankAccountDocRepository $userBankAccountDocRepository, RejectionReasonRepository $rejectionReasonRepository)
    {
        $this->caseUserBankAccountDetailsRepository = $caseUserBankAccountDetailsRepository;
        $this->caseRepository = $caseRepository;
        $this->userBankAccountRepository = $userBankAccountRepository;
        $this->bankBranchRepository = $bankBranchRepository;
        $this->drkDocRepository = $drkDocRepository;
        $this->userBankAccountDocRepository = $userBankAccountDocRepository;
        $this->rejectionReasonRepository = $rejectionReasonRepository;
    }

    /**
     * Get the UserBankAccount form details.
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

            return new CaseUserBankAccountDetailsResponse($this->caseUserBankAccountDetailsRepository->getUserBankAccountFormDetails($userId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Store UserBankAccount form details.
     *
     * @param BankBranchRequest $bankBranchRequest
     * @return array
     */
    public function getBankBranchDetailsByIfsc(BankBranchRequest $bankBranchRequest)
    {
        try {

            // get bank branch id by ifsc code
            $bankBranchDetails = $this->bankBranchRepository->findByIfscCached($bankBranchRequest->ifsc);
            if (empty($bankBranchDetails)) {
                throw new Exception;
            }
            return new BankBranchDetailsResponse($bankBranchDetails);
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store UserBankAccount form details.
     *
     * @param UserBankAccountFormRequest $userBankAccountFormRequest
     * @return array
     */
    public function storeDetails(UserBankAccountFormRequest $userBankAccountFormRequest, int $caseId)
    {
        try {

            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            $bankAccountId = $caseDetails->bank_account_id;

            // Check if the case UserBankAccount exists
            $userBankAccountDetails = $this->userBankAccountRepository->findByBankAccountIdCached($bankAccountId);

            if (empty($userBankAccountDetails)) {
                throw new Exception;
            }

            $userBankAccountId = $userBankAccountDetails->id;

            // get bank branch id by ifsc code
            $bankBranchDetails = $this->bankBranchRepository->findByIfscCached($userBankAccountFormRequest->ifsc);

            if (empty($bankBranchDetails)) {
                throw new Exception;
            }

            $userBankAccountFormRequest->merge(['bankBranchId' => $bankBranchDetails->id]);

            //update UserBankAccount deatils
            return $this->userBankAccountRepository->updateUserBankAccountDetails($userBankAccountFormRequest, $userBankAccountId);

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
     * Bank Account reject document.
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

            $bankAccountId = $caseDetails->bank_account_id;

            // Check if the case UserBankAccount exists
            $userBankAccountDetails = $this->userBankAccountRepository->findByBankAccountIdCached($bankAccountId);

            if (empty($userBankAccountDetails)) {
                throw new Exception;
            }

            $userBankAccountId = $userBankAccountDetails->id;

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


            // user bank account and doc relationship exist
            $userBankAccDoc = $this->userBankAccountDocRepository->findByUserBankAccountIdAndDocIdCached($userBankAccountId, $rejectDocumentRequest->docId);
            if (empty($userBankAccDoc)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->rejectDocument($rejectDocumentRequest);


            //update user bank account with back account and doc status
            $this->userBankAccountRepository->rejectDocument($rejectDocumentRequest, $userBankAccountId);

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
     * Bank Account approve document.
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

            $bankAccountId = $caseDetails->bank_account_id;

            // Check if the case UserBankAccount exists
            $userBankAccountDetails = $this->userBankAccountRepository->findByBankAccountIdCached($bankAccountId);

            if (empty($userBankAccountDetails)) {
                throw new Exception;
            }

            $userBankAccountId = $userBankAccountDetails->id;

            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($approveDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            // user bank account and doc relationship exist
            $userBankAccDoc = $this->userBankAccountDocRepository->findByUserBankAccountIdAndDocIdCached($userBankAccountId, $approveDocumentRequest->docId);
            if (empty($userBankAccDoc)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->approveDocument($approveDocumentRequest);


            //update user bank account with back account and doc status
            $this->userBankAccountRepository->approveDocument($approveDocumentRequest, $userBankAccountId);


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
