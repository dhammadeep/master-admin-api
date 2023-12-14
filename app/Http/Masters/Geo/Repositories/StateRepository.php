<?php

namespace App\Http\Masters\Geo\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Geo\Models\State;
use App\Http\Masters\Geo\Requests\StateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\RepoInterface\StateRepoInterface;

class StateRepository implements StateRepoInterface
{
    /**
     * Find States and get results in pagination
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
            return State::query()
            ->select('id','name','country_id','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Country:id,name')
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
            return State::getTableFields();
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
            return State::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param StateRequest $data
     *
     * @return Array
     */
    public function add(StateRequest $data)
    {
        //Create a new entry in db
        try {
            State::create([
                'name' => $data->name,
                'country_id' => $data->country_id
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
            return State::select('id','name','country_id')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param StateRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StateRequest $request, int $id)
    {
        try {
            $state = State::find($id);
            $state->name = $request->name;
            $state->country_id = $request->country_id;
            $state->save();
            return $state;
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
            State::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return State::whereIn("id", $ids)
                ->update(['status' => 'REJECTED']);
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
            State::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

             $idcollection->map(function (array $ids) {
                return State::whereIn("id", $ids)
                    ->update(['status' => 'ACTIVE']);
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
            State::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return State::whereIn("id", $ids)
                    ->update(['status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
