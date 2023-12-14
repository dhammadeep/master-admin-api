<?php

namespace App\Http\Modules\Cases\Repositories\RepoInterface;

interface CaseBriefRepoInterface
{
    public function getBriefDetails(int $caseId);
}
