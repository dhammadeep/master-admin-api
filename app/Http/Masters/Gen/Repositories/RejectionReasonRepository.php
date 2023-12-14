<?php

namespace App\Http\Masters\Gen\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Masters\Gen\Models\RejectionReason;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Requests\RejectionReasonRequest;
use App\Http\Masters\Gen\Repositories\RepoInterface\RejectionReasonRepoInterface;

class RejectionReasonRepository implements RejectionReasonRepoInterface
{
    /**
     * Find RejectionReasons and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(Request $request, int $rowsPerPage = 50)
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
            return RejectionReason::query()
            ->select('id','name','rejection_reason_type_id','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('RejectionReasonType:id,name')
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
            return RejectionReason::getTableFields();
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
            return RejectionReason::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param RejectionReasonRequest $data
     *
     * @return Array
     */
    public function add(RejectionReasonRequest $data)
    {
        //Create a new entry in db
        try {
            RejectionReason::create([
                'name' => $data->name,
                'rejection_reason_type_id' => $data->rejection_reason_type_id
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get cached doc details by rejection reason id
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $id)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'rejection-reason:' . $id;

            if ($rejectionReasonDetails = Redis::get($cacheKey)) {
                return $rejectionReasonDetails = json_decode($rejectionReasonDetails);
            }

            return $this->findById($id);

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
    public function findById(int $id)
    {
        try {
            $rejectionReasonDetails = RejectionReason::select('id','name','rejection_reason_type_id')->findOrFail($id);

            $cacheKey = 'rejection-reason:' . $id;
            Redis::setex($cacheKey, 60*60*2, json_encode($rejectionReasonDetails));

            return $rejectionReasonDetails;
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param RejectionReasonRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RejectionReasonRequest $request, int $id)
    {
        try {
            $rejectionReason = RejectionReason::find($id);
            $rejectionReason->name = $request->name;
            $rejectionReason->save();
            return $rejectionReason;
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
            RejectionReason::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return RejectionReason::whereIn("id", $ids)
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
            RejectionReason::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

             $idcollection->map(function (array $ids) {
                return RejectionReason::whereIn("id", $ids)
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
            RejectionReason::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return RejectionReason::whereIn("id", $ids)
                    ->update(['status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
