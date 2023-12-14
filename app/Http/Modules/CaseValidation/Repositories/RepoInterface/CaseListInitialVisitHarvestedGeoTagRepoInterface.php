<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use Illuminate\Http\Request;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;

interface CaseListInitialVisitHarvestedGeoTagRepoInterface
{
    public function getList(Request $request, int $rowsPerPage, CaseListRequest $dto);
    public function getTableFields();
    public function listCropType(CaseListRequest $caseListOptions);
}
