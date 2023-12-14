<?php

namespace App\Http\Masters\Gen\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Gen\Models\Language;
use App\Http\Masters\Gen\Requests\LanguageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Repositories\RepoInterface\LanguageRepoInterface;

class LanguageRepository implements LanguageRepoInterface
{
    /**
     * Find Languages and get results in pagination
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
            return Language::query()
            ->select('id','name','code','logo','file_url','status')
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
            return Language::getTableFields();
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
            return Language::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param LanguageRequest $data
     *
     * @return Array
     */
    public function add(mixed $fileUrls,LanguageRequest $data)
    {
        //Create a new entry in db
        try {
            Language::create([
                'name' => $data->name,
                'code' => $data->code,
                'logo' => $fileUrls['logo'],
                'file_url' => $fileUrls['file_url']
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
            return Language::select('id','name','code','logo','file_url')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param LanguageRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LanguageRequest $request, int $id)
    {
        try {
            $logo = $request->input('logo');
            $file_url = $request->input('file_url');
            $language = Language::find($id);
            $language->name = $request->name;
            $language->code = $request->code;
            $language->logo = $logo;
            $language->file_url = $file_url;
            $language->save();
            return $language;
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
            Language::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return Language::whereIn("id", $ids)
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
                return Language::whereIn("id", $ids)
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
                return Language::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
