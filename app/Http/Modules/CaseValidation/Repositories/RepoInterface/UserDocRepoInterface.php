<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

interface UserDocRepoInterface
{
    public function findByUserIdAndDocIdCached($userId,$docId);
    public function findByUserIdAndDocId($userId,$docId);
}
