<?php

namespace App\Http\Modules\Authentication\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Modules\Authentication\Requests\ActivityPermissionRequest;

interface ActivityPermissionRepoInterface
{
    public function add(ActivityPermissionRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(ActivityPermissionRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
