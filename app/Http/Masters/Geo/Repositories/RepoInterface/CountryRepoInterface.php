<?php

namespace App\Http\Masters\Geo\Repositories\RepoInterface;

use App\Http\Masters\Geo\Requests\CountryRequest;
use GuzzleHttp\Psr7\Request;

interface CountryRepoInterface
{
    public function add(CountryRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(CountryRequest $data, int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
