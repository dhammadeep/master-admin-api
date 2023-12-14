<?php

namespace App\Http\Modules\Cases\Services;

use Exception;
use App\Http\Modules\Cases\Repositories\CaseBriefRepository;
use App\Http\Modules\Cases\Responses\CaseBriefDetailResponseDto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CasesService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var CasesRepository
     */
    protected $caseBriefRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param CasesRepository $casesRepository
     */
    public function __construct(CaseBriefRepository $caseBriefRepository)
    {
        $this->caseBriefRepository = $caseBriefRepository;
    }

    /**
     * Get the widget structure and document details.
     *
     * @param int $caseId
     * @return array
     */
    public function getBriefDetails(int $caseId)
    {
        try {
            $caseDetails = $this->caseBriefRepository->getBriefDetails($caseId);
            if(empty($caseDetails)){
                throw new Exception();
            }
            return new CaseBriefDetailResponseDto($this->caseBriefRepository->getBriefDetails($caseId));

        }  catch (BadRequestHttpException $e) {
            throw $e;
        }catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


}
