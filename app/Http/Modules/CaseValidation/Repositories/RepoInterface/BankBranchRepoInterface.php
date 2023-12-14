<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface BankBranchRepoInterface
{
    public function findByIfscCached(string $ifsc);
    public function findById(string $ifsc);
}
