<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface LocationRepoInterface
{
    public function insertUpdateLocation(mixed $locationRequest,int $locationId);
}
