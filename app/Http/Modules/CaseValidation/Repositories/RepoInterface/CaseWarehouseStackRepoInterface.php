<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\WarehouseStackFormRequest;

interface CaseWarehouseStackRepoInterface
{
    public function getStackDetails(int $caseWarehouseId);
    public function storeStackDetails(WarehouseStackFormRequest $warehouseStackFormRequest, int $caseWarehouseId);
    public function checkUniqueStackNoWithCaseWarehouseId(int $caseWarehouseId,int $stackNo);
}
