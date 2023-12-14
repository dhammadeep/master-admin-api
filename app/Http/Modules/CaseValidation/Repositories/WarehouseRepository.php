<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\Warehouse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\warehouse\Requests\warehouseRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\WarehouseFormRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\WarehouseRepoInterface;

class WarehouseRepository implements WarehouseRepoInterface
{

    /**
     * Find Countries and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find($request, int $rowsPerPage = 50)
    {
        $rowsPerPage = $request->size;
        $order = 'desc';
        if(!empty($request->order)){
            $order = $request->order;
        }
        $orderBy = 'id';
        if(!empty($request->orderBy)){
            $orderBy = $request->orderBy;
            // if($orderBy == 'pincode'){
            //     $orderBy = 'Location.pincode';
            // }
        }
        $request = $request->except(['page','size','order','orderBy']);
        try {
            return Warehouse::query()
                ->select('drk_warehouse.id', 'drk_warehouse.location_id','drk_warehouse.warehouse_type_id','drk_warehouse.name','drk_warehouse.code','drk_warehouse.phone', 'drk_warehouse.status')
                ->when(Arr::has($request,'pincode'), function ($query) use($request){
                    $query->where('pincode', '=', $request['pincode']);
                })
                ->when(Arr::has($request,'name'), function ($query) use($request){
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function($query) use($request){
                    $query->where($request);
                })
                ->join('geo_location', 'geo_location.id', '=', 'drk_warehouse.location_id')
                ->with('Location:id,address,pincode','WarehouseType:id,name')
                ->orderBy($orderBy, $order)
                ->paginate($rowsPerPage)
                ->appends(request()->query());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get cached Warehouse details by Warehouse id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $warehouseId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'warehouse:' . $warehouseId;

            if ($warehouseDetails = Redis::get($cacheKey)) {
                $warehouseDetails = json_decode($warehouseDetails);
                return $warehouseDetails;
              /*  if($warehouseDetails->location_id){
                    return $warehouseDetails;
               }else{
                    return $this->findById($warehouseId);
               }*/
            }

            return $this->findById($warehouseId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get Warehouse details by Warehouse id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findById(int $warehouseId)
    {
        try {
            $warehouseDetails = Warehouse::select('id','location_id')->find($warehouseId);

            $cacheKey = 'warehouse:' . $warehouseId;
            Redis::setex($cacheKey, 60*60*2, json_encode($warehouseDetails));

            return $warehouseDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find record by ID
     * @param int $id
     */
    public function findwarehouseById(int $id)
    {
        try {
            return Warehouse::select('id', 'location_id','warehouse_type_id','name','code','phone')
            ->with('Location:id,address,pincode','WarehouseType:id,name')
            ->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check warehouse location exist or not.
     *
     * @param int $warehouseId
     * @param int $locationId
     * @return \Illuminate\Support\Collection
     */
    public function checkWarehouseLocationExistOrNot(int $warehouseId,int $locationId)
    {
        try {
            // Perform the check in the database and return the result
            return Warehouse::select('id','location_id')->where('id','!=',$warehouseId)->where('location_id',$locationId)->exists();

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        try {
            return Warehouse::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return Warehouse::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add a single record in the table
     *
     * @param warehouseRequest $data
     *
     * @return Array
     */
    public function add(warehouseRequest $data)
    {
        //Create a new entry in db
        try {
            Warehouse::create([
                'location_id' => $data->location_id,
                'warehouse_type_id' => $data->warehouse_type_id,
                'name' => $data->name,
                'code' => $data->code,
                'phone' => $data->phone
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param WarehouseRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(warehouseRequest $request, int $id)
    {
        try {
            $warehouse = Warehouse::find($id);
            $warehouse->location_id = $request->location_id;
            $warehouse->warehouse_type_id = $request->warehouse_type_id;
            $warehouse->name = $request->name;
            $warehouse->code = $request->code;
            $warehouse->phone = $request->phone;
            $warehouse->save();
            return $warehouse;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusReject(array $id)
    {
        try {
            Warehouse::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return Warehouse::whereIn("id", $ids)
                ->update(['Status' => 'REJECTED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusFinalize(array $id)
    {
        try {
            $idcollection = collect($id);

             $idcollection->map(function (array $ids) {
                return Warehouse::whereIn("id", $ids)
                    ->update(['Status' => 'Active']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusApprove(array $id)
    {
        try {
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return Warehouse::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
