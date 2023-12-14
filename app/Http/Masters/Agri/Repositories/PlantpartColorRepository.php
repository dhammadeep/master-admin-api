<?php

namespace App\Http\Masters\Agri\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Agri\Models\PlantpartColor;
use App\Http\Masters\Agri\Requests\PlantpartColorRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\RepoInterface\PlantpartColorRepoInterface;

class PlantpartColorRepository implements PlantpartColorRepoInterface
{
    /**
     * Find PlantpartColors and get results in pagination
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
        }
        $request = $request->except(['page','size','order','orderBy']);
        try {
            return PlantpartColor::query()
            ->select('id','commodity_id','phenophase_id','name','hexcode','weightage','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            //->with('State:id,name')
            ->orderBy($orderBy, $order)->paginate($rowsPerPage)
            ->appends(request()->query());
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
            return PlantpartColor::getTableFields();
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
            return PlantpartColor::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param PlantpartColorRequest $data
     *
     * @return Array
     */
    public function add(PlantpartColorRequest $data)
    {
        //Create a new entry in db
        try {
            PlantpartColor::create([
                'commodity_id' => $data->commodity_id,
                'phenophase_id' => $data->phenophase_id,
                'name' => $data->name,
                'hexcode' => $data->hexcode,
                'weightage' => $data->weightage
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find record by ID
     * @param int $id
     */
    public function findById(int $id)
    {
        try {
            return PlantpartColor::select('id','commodity_id','phenophase_id','name','hexcode','weightage')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param PlantpartColorRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PlantpartColorRequest $request, int $id)
    {
        try {
            $plantpartColor = PlantpartColor::find($id);
            $plantpartColor->commodity_id = $request->commodity_id;
            $plantpartColor->phenophase_id = $request->phenophase_id;
            $plantpartColor->name = $request->name;
            $plantpartColor->hexcode = $request->hexcode;
            $plantpartColor->weightage = $request->weightage;
            $plantpartColor->save();
            return $plantpartColor;
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
            PlantpartColor::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return PlantpartColor::whereIn("id", $ids)
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
                return PlantpartColor::whereIn("id", $ids)
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
                return PlantpartColor::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
