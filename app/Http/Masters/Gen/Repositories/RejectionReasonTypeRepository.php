<?php

namespace App\Http\Masters\Gen\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Gen\Models\RejectionReasonType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\RejectionReasonTypeRequest;
use App\Http\Masters\Gen\Repositories\RepoInterface\RejectionReasonTypeRepoInterface;

class RejectionReasonTypeRepository implements RejectionReasonTypeRepoInterface
{
    /**
     * Find RejectionReasonTypes and get results in pagination
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
            return RejectionReasonType::query()
            ->select('id','name','status')
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
            return RejectionReasonType::getTableFields();
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
            return RejectionReasonType::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param RejectionReasonTypeRequest $data
     *
     * @return Array
     */
    public function add(RejectionReasonTypeRequest $data)
    {
        //Create a new entry in db
        try {
            RejectionReasonType::create([
                'name' => $data->name,
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
            return RejectionReasonType::select('id','name')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param RejectionReasonTypeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RejectionReasonTypeRequest $request, int $id)
    {
        try {
            $rejectionReasonType = RejectionReasonType::find($id);
            $rejectionReasonType->name = $request->name;
            $rejectionReasonType->save();
            return $rejectionReasonType;
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
            RejectionReasonType::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return RejectionReasonType::whereIn("id", $ids)
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
            RejectionReasonType::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

             $idcollection->map(function (array $ids) {
                return RejectionReasonType::whereIn("id", $ids)
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
            RejectionReasonType::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return RejectionReasonType::whereIn("id", $ids)
                    ->update(['status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
