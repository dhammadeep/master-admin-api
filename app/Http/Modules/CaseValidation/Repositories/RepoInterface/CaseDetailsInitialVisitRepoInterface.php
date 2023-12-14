<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\CaseListRequest;

interface CaseDetailsInitialVisitRepoInterface
{
    public function find(int $rowsPerPage, CaseListRequest $dto);
    public function getTableFields();
}
