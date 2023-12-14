<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface UserCompanyRepoInterface
{
    public function findByUserIdCached(int $userId);
    public function findByUserId(int $userId);
}
