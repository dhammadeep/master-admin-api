<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;

interface CompanyDocRepoInterface
{
    public function findByCompanyIdAndDocIdCached($companyId,$docId);
    public function findByCompanyIdAndDocId($companyId,$docId);
}
