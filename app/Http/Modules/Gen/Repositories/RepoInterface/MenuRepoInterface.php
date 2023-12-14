<?php

namespace App\Http\Modules\Gen\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Modules\Gen\Requests\MenuRequest;

interface MenuRepoInterface
{
    public function add(MenuRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(MenuRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
