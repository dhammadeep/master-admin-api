<?php

namespace App\Http\Masters\Agri\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Agri\Models\QualityParameterRange;
use App\Http\Masters\Agri\Requests\QualityParameterRangeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\RepoInterface\QualityParameterRangeRepoInterface;

class QualityParameterRangeRepository implements QualityParameterRangeRepoInterface
{
    /**
     * Find QualityParameterRanges and get results in pagination
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
            return QualityParameterRange::query()
            ->select('id','commodity_id','parameter_id','quality_band_id','min_value','max_value','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Commodity:id,name','Parameter:id,name','Quality:id,name')
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
            return QualityParameterRange::getTableFields();
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
            return QualityParameterRange::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param QualityParameterRangeRequest $data
     *
     * @return Array
     */
    public function add(QualityParameterRangeRequest $data)
    {
        //Create a new entry in db
        try {
            QualityParameterRange::create([
                'commodity_id' => $data->commodity_id,
                'parameter_id' => $data->parameter_id,
                'min_value' => $data->min_value,
                'max_value' => $data->max_value,
                'quality_band_id' => $data->quality_band_id,
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
            return QualityParameterRange::select('id','commodity_id','parameter_id','quality_band_id','min_value','max_value')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param QualityParameterRangeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QualityParameterRangeRequest $request, int $id)
    {
        try {
            $qualityParameterRange = QualityParameterRange::find($id);
            $qualityParameterRange->commodity_id = $request->commodity_id;
            $qualityParameterRange->parameter_id = $request->parameter_id;
            $qualityParameterRange->quality_band_id = $request->quality_band_id;
            $qualityParameterRange->min_value = $request->min_value;
            $qualityParameterRange->max_value = $request->max_value;
            $qualityParameterRange->save();
            return $qualityParameterRange;
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
            QualityParameterRange::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return QualityParameterRange::whereIn("id", $ids)
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
                return QualityParameterRange::whereIn("id", $ids)
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
                return QualityParameterRange::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
