<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\WarehouseDoc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\DocumentApprovalRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\WarehouseDocRepoInterface;

class WarehouseDocRepository implements WarehouseDocRepoInterface
{
    /**
     * get cached warehouse doc details, to check whether case warehouse id and doc id is exist or not
     *
     * @param int $warehouseId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByWarehouseIdAndDocIdCached($caseWarehouseId, $docId)
    {
        Redis::flushDB();
        try {
            $cacheKey = 'warehouse-doc:' . $docId;

            if ($warehouseDocDetails = Redis::get($cacheKey)) {

                return $warehouseDocDetails = json_decode($warehouseDocDetails);
            }

            return $this->findByWarehouseIdAndDocId($caseWarehouseId, $docId);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get user doc details by case warehouse id and doc id
     *
     * @param int $caseWarehouseId
     * @param int $docId
     * @return \Illuminate\Support\Collection
     */
    public function findByWarehouseIdAndDocId($caseWarehouseId, $docId)
    {
        try {

            $warehouseDocDetails = WarehouseDoc::select('case_warehouse_id', 'doc_id')->where('case_warehouse_id', $caseWarehouseId)->where('doc_id', $docId)->first();
            $cacheKey = 'warehouse-doc:' . $docId;
            Redis::setex($cacheKey, 60 * 60 * 2, json_encode($warehouseDocDetails));

            return $warehouseDocDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
