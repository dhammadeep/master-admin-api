<?php

namespace App\Http\Masters\Geo\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Geo\Requests\LocationRequest;

interface LocationRepoInterface
{
    public function add(LocationRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(LocationRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
