<?php

namespace App\Repositories\Geographical\Country\RepoInterface;

use App\Http\Requests\Geographical\Country\CountryRequest;

interface CountryRepoInterface
{
    public function add(CountryRequest $data);

    public function find(string $on = "", string $search = "", int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(CountryRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
