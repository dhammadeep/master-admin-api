<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Masters\Gen\Repositories\RejectionReasonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\DrkDocRepository;
use App\Http\Modules\CaseValidation\Requests\WarehouseFormRequest;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\CaseValidation\Repositories\WarehouseRepository;
use App\Http\Modules\CaseValidation\Requests\WarehouseStackFormRequest;
use App\Http\Modules\CaseValidation\Responses\WarehouseDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\WarehouseDocRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseWarehouseRepository;
use App\Http\Modules\CaseValidation\Responses\WarehouseStackDetailsResponse;
use App\Http\Modules\CaseValidation\Repositories\CaseWarehouseStackRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseWarehouseDetailsRepository;

class WarehouseDocService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CaseWarehouseDetailsRepository
     */
    protected $caseWarehouseDetailsRepository, $warehouseRepository, $caseWarehouseRepository, $caseWarehouseStackRepository, $warehouseDocRepository, $caseRepository, $drkDocRepository, $rejectionReasonRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CaseWarehouseDetailsRepository $caseWarehouseDetailsRepository, $warehouseDocRepository
     */
    public function __construct(CaseWarehouseDetailsRepository $caseWarehouseDetailsRepository, WarehouseRepository $warehouseRepository, CaseWarehouseRepository $caseWarehouseRepository, CaseWarehouseStackRepository $caseWarehouseStackRepository, WarehouseDocRepository $warehouseDocRepository, CasesRepository $caseRepository, DrkDocRepository $drkDocRepository, RejectionReasonRepository $rejectionReasonRepository)
    {
        $this->caseWarehouseDetailsRepository = $caseWarehouseDetailsRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->caseWarehouseRepository = $caseWarehouseRepository;
        $this->caseWarehouseStackRepository = $caseWarehouseStackRepository;
        $this->warehouseDocRepository = $warehouseDocRepository;
        $this->caseRepository = $caseRepository;
        $this->drkDocRepository = $drkDocRepository;
        $this->rejectionReasonRepository = $rejectionReasonRepository;
    }

    /**
     * Get the warehouse form details.
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

            return new WarehouseDetailsResponse($this->caseWarehouseDetailsRepository->getWarehouseDocFormDetails($caseId));
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the warehouse stack details.
     *
     * @param int $caseId
     * @return array
     */
    public function getStackDetails(int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case Warehouse exists
            $caseWarehouseDetails = $this->caseWarehouseRepository->findByCaseIdCached($caseId);

            if (empty($caseWarehouseDetails)) {
                throw new Exception;
            }

            $caseWarehouseId = $caseWarehouseDetails->id;

            $caseWarehouseStackDetails = $this->caseWarehouseStackRepository->getStackDetails($caseWarehouseId);

            return new WarehouseStackDetailsResponse($caseWarehouseStackDetails);
        } catch (BadRequestHttpException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store warehouse form details.
     *
     * @param WarehouseFormRequest $warehouseFormRequest
     * @return array
     */
    public function storeDetails(WarehouseFormRequest $warehouseFormRequest, int $caseId)
    {
        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case Warehouse exists
            $caseWarehouseDetails = $this->caseWarehouseRepository->findByCaseIdCached($caseId);

            if (empty($caseWarehouseDetails)) {
                throw new Exception;
            }

            $warehouseId = $caseWarehouseDetails->warehouse_id;

            // Check if Warehouse id exist or not
            $warehouseDetails = $this->warehouseRepository->findByIdCached($warehouseId);

            if (empty($warehouseDetails)) {
                throw new Exception;
            }

            //update case Warehouse details
            return $this->caseWarehouseRepository->updateCaseWarehouseDetails($warehouseFormRequest, $warehouseId);

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
     * Store warehouse stack form details.
     *
     * @param WarehouseStackFormRequest $warehouseStackFormRequest
     * @return array
     */
    public function storeStackDetails(WarehouseStackFormRequest $warehouseStackFormRequest, int $caseId)
    {

        try {
            // Check if the case exists
            $caseDetails = $this->caseRepository->findById($caseId);

            if (empty($caseDetails)) {
                throw new Exception;
            }

            // Check if the case Warehouse exists
            $caseWarehouseDetails = $this->caseWarehouseRepository->findByCaseIdCached($caseId);

            if (empty($caseWarehouseDetails)) {
                throw new Exception;
            }

            $caseWarehouseId = $caseWarehouseDetails->id;

            // Check if the case Warehouse and stack no exists
            $caseWarehouseWithStackNoExist = $this->caseWarehouseStackRepository->checkUniqueStackNoWithCaseWarehouseId($caseWarehouseId, $warehouseStackFormRequest->stackNo);

            if ($caseWarehouseWithStackNoExist) {
                throw new Exception;
            }

            //insert/update case Warehouse stack details
            return $this->caseWarehouseStackRepository->storeStackDetails($warehouseStackFormRequest, $caseWarehouseId);

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

    /**
     * Warehouse reject document.
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

            // Check if the case Warehouse exists
            $caseWarehouseDetails = $this->caseWarehouseRepository->findByCaseIdCached($caseId);

            if (empty($caseWarehouseDetails)) {
                throw new Exception;
            }

            $caseWarehouseId = $caseWarehouseDetails->id;

            // Check if the case warehouse Doc exists
            $warehouseDocDetails = $this->warehouseDocRepository->findByWarehouseIdAndDocId($caseWarehouseId, $rejectDocumentRequest->docId);

            if (empty($warehouseDocDetails)) {
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

            //update warehouse with warehouse doc status
            $this->caseWarehouseRepository->rejectDocument($rejectDocumentRequest, $caseWarehouseId);

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

            // Check if the case Warehouse exists
            $caseWarehouseDetails = $this->caseWarehouseRepository->findByCaseIdCached($caseId);
            if (empty($caseWarehouseDetails)) {
                throw new Exception;
            }

            $caseWarehouseId = $caseWarehouseDetails->id;

            // Check if the case warehouse Doc exists
            $warehouseDocDetails = $this->warehouseDocRepository->findByWarehouseIdAndDocIdCached($caseWarehouseId, $approveDocumentRequest->docId);
            if (empty($warehouseDocDetails)) {
                throw new Exception;
            }

            // Check if the document exists
            $docDetails = $this->drkDocRepository->findByIdCached($approveDocumentRequest->docId);

            if (empty($docDetails)) {
                throw new Exception;
            }

            //update drk_doc status
            $this->drkDocRepository->approveDocument($approveDocumentRequest);

            //update warehouse with warehouse doc status
            $this->caseWarehouseRepository->approveDocument($approveDocumentRequest, $caseWarehouseId);

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
