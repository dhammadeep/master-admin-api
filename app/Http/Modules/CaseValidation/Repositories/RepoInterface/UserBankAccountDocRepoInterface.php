<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;

interface UserBankAccountDocRepoInterface
{
    public function findByUserBankAccountIdAndDocIdCached($userBankAccountId,$docId);
    public function findByUserBankAccountIdAndDocId($userBankAccountId,$docId);
}
