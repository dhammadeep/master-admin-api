<?php

namespace App\Http\Masters\Common\Repositories\RepoInterface;

use App\Http\Masters\Common\Requests\DropdownRequest;

interface DropdownRepoInterface
{

    public function findCountry();
    public function findState(int $id);
    public function findDistrict(int $id);
    public function findCity(int $districtId);
    public function findRejectionReason(int $id);
    public function findById(int $id);

}
