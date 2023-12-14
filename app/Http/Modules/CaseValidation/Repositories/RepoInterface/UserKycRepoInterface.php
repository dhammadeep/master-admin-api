<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\BasicKycFormRequest;

interface UserKycRepoInterface
{
    public function findByUserIdCached(int $userId);
    public function findByUserId(int $userId);
    public function checkUserLocationExistOrNot(int $userId,int $locationId);
    public function insertUpdateUserKyc(BasicKycFormRequest $basicKycFormRequest,int $userId);
}
