<?php

namespace App\Http\Modules\DocWidgetStructure\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\DocWidgetStructure\Models\DocWidgetDetailsWithUrl;
use App\Http\Modules\DocWidgetStructure\Repositories\RepoInterface\DocWidgetRepoInterface;

class DocWidgetRepository implements DocWidgetRepoInterface
{

    /**
     * Get the widget structure and document details from the database.
     *
     * @param int $caseId
     * @param int $widgetId
     * @return \Illuminate\Support\Collection
     */
    public function getWidgetStructure(int $caseId,int $widgetId)
    {
        try {
            return DB::select(
                "call GetDocumentWidgetStructureWithUploadedFileUrl($caseId,$widgetId)"
            );
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
