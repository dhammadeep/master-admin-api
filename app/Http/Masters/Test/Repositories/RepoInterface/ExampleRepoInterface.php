<?php

namespace App\Http\Masters\Test\Repositories\RepoInterface;

use App\Http\Masters\Test\Requests\ExampleRequest;

interface ExampleRepoInterface
{
    public function add(ExampleRequest $data);

    public function find(string $on = "", string $search = "", int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(ExampleRequest $data, int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
