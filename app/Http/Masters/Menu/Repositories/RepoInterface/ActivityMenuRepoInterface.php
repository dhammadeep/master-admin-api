<?php

namespace App\Http\Masters\Menu\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Menu\Requests\ActivityMenuRequest;

interface ActivityMenuRepoInterface
{
    public function add(ActivityMenuRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(ActivityMenuRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
