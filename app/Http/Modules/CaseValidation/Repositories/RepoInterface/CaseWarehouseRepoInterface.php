<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

interface CaseWarehouseRepoInterface
{
    public function findByCaseIdCached(int $caseId);
    public function findByCaseId(int $caseId);
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $warehouseId);
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $warehouseId);

}
