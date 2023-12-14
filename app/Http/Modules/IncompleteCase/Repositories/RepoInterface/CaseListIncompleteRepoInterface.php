<?php

namespace App\Http\Modules\IncompleteCase\Repositories\RepoInterface;


interface CaseListIncompleteRepoInterface
{
    public function getList(int $rowsPerPage);
    public function getTableFields();
}
