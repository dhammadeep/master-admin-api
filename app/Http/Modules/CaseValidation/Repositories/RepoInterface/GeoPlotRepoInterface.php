<?php

namespace App\Http\Modules\CaseValidation\Repositories\RepoInterface;

use App\Http\Modules\CaseValidation\Requests\GeoPlottingApprovalRequest;

interface GeoPlotRepoInterface
{
    public function findByIdCached(int $geoPlotId);
    public function findById(int $geoPlotId);
    public function geoPlotReject(GeoPlottingApprovalRequest $geoPlottingApprovalRequest,$geoPlotId);
    public function geoPlotApprove($geoPlotId);
}
