<?php

namespace App\Http\Masters\Agri\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Agri\Models\Parameter;
use App\Http\Masters\Agri\Requests\ParameterRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\RepoInterface\ParameterRepoInterface;

class ParameterRepository implements ParameterRepoInterface
{
    /**
     * Find Parameters and get results in pagination
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
            return Parameter::query()
            ->select('id','name','scheme','description','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            //->with('State:id,name')
            ->orderBy($orderBy, $order)->paginate($rowsPerPage)
            ->appends(request()->query());
        } catch (Exception $e) {
            dd($e);
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
            return Parameter::getTableFields();
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
            return Parameter::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param ParameterRequest $data
     *
     * @return Array
     */
    public function add(ParameterRequest $data)
    {
        //Create a new entry in db
        try {
            Parameter::create([
                'name' => $data->name,
                'description' => $data->description,
                'scheme' => $data->scheme,
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
            return Parameter::select('id','name','description','scheme')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ParameterRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ParameterRequest $request, int $id)
    {
        try {
            $parameter = Parameter::find($id);
            $parameter->name = $request->name;
            $parameter->description = $request->description;
            $parameter->scheme = $request->scheme;
            $parameter->save();
            return $parameter;
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
            Parameter::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return Parameter::whereIn("id", $ids)
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
                return Parameter::whereIn("id", $ids)
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
                return Parameter::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
