<?php

namespace App\Http\Modules\Authentication\Repositories\RepoInterface;

use App\Http\Modules\Authentication\Requests\PermissionRequest;



interface PermissionRepositoryInterface
{

    public function create(PermissionRequest $data);
}
