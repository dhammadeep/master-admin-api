<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

interface UserBankAccountRepoInterface
{
    public function findByBankAccountIdCached(int $bankAccountId);
    public function findByBankAccountId(int $bankAccountId);
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $userBankAccountId);
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $userBankAccountId);
}
