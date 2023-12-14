<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface FarmDocRepoInterface
{
    public function findByFarmIdAndDocIdCached($farmId, $docId);
    public function findByFarmIdAndDocId($farmId, $docId);
}
