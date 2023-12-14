<?php

namespace App\Http\Masters\Gen\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Gen\Requests\BankBranchRequest;

interface BankBranchRepoInterface
{
    public function add(BankBranchRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(BankBranchRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
