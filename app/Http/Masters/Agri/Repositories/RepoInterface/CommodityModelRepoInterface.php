<?php

namespace App\Http\Masters\Agri\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Agri\Requests\CommodityModelRequest;

interface CommodityModelRepoInterface
{
    public function add(CommodityModelRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(CommodityModelRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
