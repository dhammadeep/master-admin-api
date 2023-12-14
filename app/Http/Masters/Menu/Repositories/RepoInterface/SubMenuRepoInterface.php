<?php

namespace App\Http\Masters\Menu\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Menu\Requests\SubMenuRequest;

interface SubMenuRepoInterface
{
    public function add(SubMenuRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(SubMenuRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
