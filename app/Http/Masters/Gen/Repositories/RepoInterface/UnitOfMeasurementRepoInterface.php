<?php

namespace App\Http\Masters\Gen\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Gen\Requests\UnitOfMeasurementRequest;

interface UnitOfMeasurementRepoInterface
{
    public function add(UnitOfMeasurementRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(UnitOfMeasurementRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
