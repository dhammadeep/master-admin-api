<?php

namespace App\Http\Masters\Agri\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Agri\Models\PhenophaseDuration;
use App\Http\Masters\Agri\Requests\PhenophaseDurationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\RepoInterface\PhenophaseDurationRepoInterface;

class PhenophaseDurationRepository implements PhenophaseDurationRepoInterface
{
    /**
     * Find PhenophaseDurations and get results in pagination
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
            return PhenophaseDuration::query()
            ->select('id','commodity_id','variety_id','phenophase_id','start_das','end_das','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Commodity:id,name','Variety:id,name','Phenophase:id,name')
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
            return PhenophaseDuration::getTableFields();
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
            return PhenophaseDuration::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param PhenophaseDurationRequest $data
     *
     * @return Array
     */
    public function add(PhenophaseDurationRequest $data)
    {
        //Create a new entry in db
        try {
            PhenophaseDuration::create([
                'commodity_id' => $data->commodity_id,
                'variety_id' => $data->variety_id,
                'phenophase_id' => $data->phenophase_id,
                'start_das' => $data->start_das,
                'end_das' => $data->end_das
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
            return PhenophaseDuration::select('id','commodity_id','variety_id','phenophase_id','start_das','end_das')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param PhenophaseDurationRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PhenophaseDurationRequest $request, int $id)
    {
        try {
            $phenophaseDuration = PhenophaseDuration::find($id);
            $phenophaseDuration->commodity_id = $request->commodity_id;
            $phenophaseDuration->variety_id = $request->variety_id;
            $phenophaseDuration->phenophase_id = $request->phenophase_id;
            $phenophaseDuration->start_das = $request->start_das;
            $phenophaseDuration->end_das = $request->end_das;
            $phenophaseDuration->save();
            return $phenophaseDuration;
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
            PhenophaseDuration::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return PhenophaseDuration::whereIn("id", $ids)
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
                return PhenophaseDuration::whereIn("id", $ids)
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
                return PhenophaseDuration::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
