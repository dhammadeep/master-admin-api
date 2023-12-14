<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseUserBankAccountDetailsRepoInterface
{
    public function getUserBankAccountFormDetails(int $userId);
}
