<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface WarehouseDocRepoInterface
{
    public function findByWarehouseIdAndDocIdCached($caseWarehouseId,$docId);
    public function findByWarehouseIdAndDocId($caseWarehouseId,$docId);
}
