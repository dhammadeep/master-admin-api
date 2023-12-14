<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface CaseUserCompanyDetailsRepoInterface
{
    public function getCompanyFormDetails(int $userId);
}
