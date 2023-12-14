<?php

namespace App\Http\Masters\Geo\Repositories;

use Exception;
use Illuminate\Support\Arr;
use GuzzleHttp\Psr7\Request;
use App\Http\Masters\Geo\Models\Country;
use App\Http\Masters\Geo\Requests\CountryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\RepoInterface\CountryRepoInterface;

class CountryRepository implements CountryRepoInterface
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
        }
        $request = $request->except(['page','size','order','orderBy']);
        try {
            return Country::query()
                ->select('id', 'name','iso2', 'status')
                ->when(Arr::has($request,'name'), function ($query) use($request){
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function($query) use($request){
                    $query->where($request);
                })
                //->with('State:ID,Name')
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
            return Country::getTableFields();
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
            return Country::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param CountryRequest $data
     *
     * @return Array
     */
    public function add(CountryRequest $data)
    {
        //Create a new entry in db
        try {
            Country::create([
                'name' => $data->name,
                'iso2' => $data->iso2,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find record by id
     * @param int $id
     */
    public function findById(int $id)
    {
        try {
            return Country::select('id','name','iso2', 'status')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CountryRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CountryRequest $request, int $id)
    {
        try {
            $country = Country::find($id);
            $country->name = $request->name;
            $country->iso2 = $request->iso2;
            $country->save();
            return $country;
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
            Country::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return Country::whereIn("id", $ids)
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
            Country::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return Country::whereIn("id", $ids)
                    ->update(['status' => 'PENDING']);
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
            Country::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return Country::whereIn("id", $ids)
                    ->update(['status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
