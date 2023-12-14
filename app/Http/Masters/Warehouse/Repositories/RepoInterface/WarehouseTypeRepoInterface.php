<?php

namespace App\Http\Masters\Warehouse\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Warehouse\Requests\WarehouseTypeRequest;

interface WarehouseTypeRepoInterface
{
    public function add(WarehouseTypeRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(WarehouseTypeRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
