<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

interface DrkDocRepoInterface
{
    //public function documentApproval(DocValidationRequest $data, int $id);
    //public function getTableFields();

    public function findByIdCached(int $docId);
    public function findById(int $docId);
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest);
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest);
}
