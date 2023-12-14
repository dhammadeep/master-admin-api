<?php

namespace App\Http\Modules\Authentication\Repositories\RepoInterface;
use App\Http\Modules\Authentication\Requests\RoleRequest;
use GuzzleHttp\Psr7\Request;


interface RoleRepositoryInterface
{
    public function getAll(Request $request, int $rowsPerPage = 50);

    public function create(RoleRequest $data);

    public function addPermissionsToRole($roleId, array $permissions);

    public function getRoleByID(int $id);

    public function update(RoleRequest $data, int $id);

    public function delete(int $id);
}
