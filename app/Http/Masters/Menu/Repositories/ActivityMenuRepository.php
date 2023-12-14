<?php

namespace App\Http\Masters\Menu\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Menu\Models\ActivityMenu;
use App\Http\Modules\Authentication\Models\Activity;
use App\Http\Masters\Menu\Requests\ActivityMenuRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Menu\Repositories\RepoInterface\ActivityMenuRepoInterface;

class ActivityMenuRepository implements ActivityMenuRepoInterface
{
    /**
     * Find ActivityMenus and get results in pagination
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
             return Activity::query()
            ->select('id','name')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('SubMenu:id,name')
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
            return ActivityMenu::getTableFields();
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
            return ActivityMenu::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param ActivityMenuRequest $data
     *
     * @return Array
     */
    public function add(ActivityMenuRequest $data)
    {
        //Create a new entry in db
        try {
            $activityId = $data->activity_id;
            $subMenuIds = explode(',',$data->sub_menu_id);
            $data = collect($subMenuIds)->map(function ($subMenuId) use($activityId) {
                return [
                    'activity_id' => $activityId,
                    'sub_menu_id' => $subMenuId
                ];
            })->all();
            ActivityMenu::upsert($data,$activityId,['activity_id','sub_menu_id']);
        } catch (Exception $e) {
            dd($e);
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
            return ActivityMenu::select('activity_id','sub_menu_id')
            ->where('activity_id','=',$id)
            ->get();
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ActivityMenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ActivityMenuRequest $request, int $id)
    {
        try {
            $activityId = $id;
            $subMenuIds = explode(',',$request->sub_menu_id);
            $data = collect($subMenuIds)->map(function ($subMenuId) use($activityId) {
                return [
                    'activity_id' => $activityId,
                    'sub_menu_id' => $subMenuId
                ];
            })->all();
            //delete all
            $activity = ActivityMenu::where('activity_id',$activityId)->delete();
            //Insert
            $activityMenu = ActivityMenu::insert($data,$activityId);
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
            ActivityMenu::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return ActivityMenu::whereIn("id", $ids)
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
                return ActivityMenu::whereIn("id", $ids)
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
                return ActivityMenu::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }


}
