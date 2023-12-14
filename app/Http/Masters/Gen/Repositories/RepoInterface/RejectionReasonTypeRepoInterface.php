<?php

namespace App\Http\Masters\Gen\Repositories\RepoInterface;

use App\Http\Masters\Gen\Requests\RejectionReasonTypeRequest;

interface RejectionReasonTypeRepoInterface
{
    public function add(RejectionReasonTypeRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(RejectionReasonTypeRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
