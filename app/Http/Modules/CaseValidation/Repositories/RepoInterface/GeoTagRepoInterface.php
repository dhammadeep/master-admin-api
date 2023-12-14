<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\GeoTagApprovalRequest;

interface GeoTagRepoInterface
{
    public function findByIdCached(int $geoTagId);
    public function findById(int $geoTagId);
    public function geoTagReject(GeoTagApprovalRequest $geoTagApprovalRequest,$geoTagId);
    public function geoTagApprove($geoTagId);
}
