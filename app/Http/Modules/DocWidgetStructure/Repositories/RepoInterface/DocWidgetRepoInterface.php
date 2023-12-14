<?php

namespace App\Http\Modules\DocWidgetStructure\Repositories\RepoInterface;

interface DocWidgetRepoInterface
{
    public function getWidgetStructure(int $caseId,int $widgetId);
}
