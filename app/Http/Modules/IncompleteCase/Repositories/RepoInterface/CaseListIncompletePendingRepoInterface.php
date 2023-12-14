<?php

namespace App\Http\Modules\IncompleteCase\Repositories\RepoInterface;

use Illuminate\Http\Request;


interface CaseListIncompletePendingRepoInterface
{
    public function getList(Request $request, int $rowsPerPage);
    public function getTableFields();
}
