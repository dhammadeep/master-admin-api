<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Masters\warehouse\Requests\warehouseRequest;


interface WarehouseRepoInterface
{
    public function findByIdCached(int $warehouseId);
    public function findById(int $warehouseId);
    public function findwarehouseById(int $warehouseId);
    public function checkWarehouseLocationExistOrNot(int $warehouseId,int $locationId);
    public function update(warehouseRequest $data, int $id);
}
