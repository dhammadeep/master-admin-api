<?php

namespace App\Http\Masters\Gen\Repositories\RepoInterface;

use Illuminate\Http\Request;
use App\Http\Masters\Gen\Requests\RejectionReasonRequest;

interface RejectionReasonRepoInterface
{
    public function add(RejectionReasonRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findByIdCached(int $id);

    public function findById(int $id);

    public function update(RejectionReasonRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
