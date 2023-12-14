<?php

namespace App\Http\Modules\Authentication\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use App\Http\Modules\Authentication\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Http\Modules\Authentication\Models\ActivityPermission;
use App\Http\Modules\Authentication\Requests\ActivityPermissionRequest;
use App\Http\Modules\Authentication\Repositories\RepoInterface\ActivityPermissionRepoInterface;

class ActivityPermissionRepository implements ActivityPermissionRepoInterface
{
    /**
     * Find ActivityPermissions and get results in pagination
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
            return ActivityPermission::query()
            ->select('activity_id','permission_id')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Permission:id,name','Activity:id,name')
            ->paginate($rowsPerPage)
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
            return ActivityPermission::getTableFields();
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
            return ActivityPermission::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param ActivityPermissionRequest $data
     *
     * @return Array
     */
    public function add(ActivityPermissionRequest $data)
    {
        //Create a new entry in db
        try {
            $role = Role::findById($data['activity_id']);

            $permission = Permission::select('name')
            ->whereIn('id',explode(',',$data['permission_id']))
            ->get()->pluck('name');
            $role->givePermissionTo($permission);

            // ActivityPermission::create([
            //     'activity_id' => $data->activity_id,
            //     'permission_id' => $data->permission_id,
            // ]);
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
            return ActivityPermission::select('activity_id','permission_id')->findOrFail($id);
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
     * @param ActivityPermissionRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ActivityPermissionRequest $request, int $id)
    {
        try {
            $role = Role::findById($request['activity_id']);

            $permission = Permission::select('name')
            ->whereIn('id',explode(',',$request['permission_id']))
            ->get()->pluck('name');
            $role->syncPermissions($permission);
            // $activityPermission = ActivityPermission::find($id);
            // $activityPermission->activity_id = $request->activity_id;
            // $activityPermission->permission_id = $request->permission_id;
            // $activityPermission->save();
            // return $activityPermission;
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
            ActivityPermission::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return ActivityPermission::whereIn("id", $ids)
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
                return ActivityPermission::whereIn("id", $ids)
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
                return ActivityPermission::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
