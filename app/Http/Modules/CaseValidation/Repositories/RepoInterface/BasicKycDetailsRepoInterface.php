<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface BasicKycDetailsRepoInterface
{
    public function getBasicKycFormDetails(int $userId);
}
