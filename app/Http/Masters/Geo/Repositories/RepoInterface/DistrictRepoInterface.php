<?php

namespace App\Http\Masters\Geo\Repositories\RepoInterface;

use GuzzleHttp\Psr7\Request;
use App\Http\Masters\Geo\Requests\DistrictRequest;

interface DistrictRepoInterface
{
    public function add(DistrictRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(DistrictRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
