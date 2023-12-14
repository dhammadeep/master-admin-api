<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\CompanyFormRequest;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

interface CompanyRepoInterface
{
    public function findByIdCached(int $companyId);
    public function findById(int $companyId);
    public function checkCompanyLocationExistOrNot(int $companyId,int $locationId);
    public function updateCompanyDetails(CompanyFormRequest $companyFormRequest,int $companyId);
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $companyId);
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $companyId);
}
