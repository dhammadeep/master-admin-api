<?php
namespace App\Http\Modules\Gen\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\Gen\Models\Menu;
use App\Http\Masters\Menu\Models\SubMenu;
use App\Http\Modules\Gen\Models\MenuActivity;
use App\Http\Modules\Gen\Requests\MenuRequest;
use App\Http\Modules\Authentication\Models\Activity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Gen\Repositories\RepoInterface\MenuRepoInterface;

class MenuRepository implements MenuRepoInterface
{
    /**
     * Find Menus and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find($request, int $rowsPerPage = 50)
    {
        $rowsPerPage = $request->size;
        $order = 'asc';
        if(!empty($request->order)){
            $order = $request->order;
        }
        $orderBy = 'menu_order';
        if(!empty($request->orderBy)){
            $orderBy = $request->orderBy;
        }
        $request = $request->except(['page','size','order','orderBy']);
        try {
            $activityIds = [];
            // DB::enableQueryLog();
            return Menu::query()
            ->select('id','name','menu_order')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('SubMenu:id,menu_id,submenu_order,name')
            ->orderBy($orderBy, $order)->paginate($rowsPerPage)
            ->appends(request()->query());
            // dump(DB::getQueryLog());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Menus and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function findMenu($request, int $rowsPerPage = 50)
    {
        $rowsPerPage = $request->size;
        $order = 'asc';
        if(!empty($request->order)){
            $order = $request->order;
        }
        $orderBy = 'menu_order';
        if(!empty($request->orderBy)){
            $orderBy = $request->orderBy;
        }
        $activity = Activity::select('id')->whereIn('name',$request['activity'])->get();
        $activityIds = $activity->map(function ($activity) {
            return $activity->id;
        })->all();
        $request = $request->except(['page','size','order','orderBy','activity']);
        try {
            // DB::enableQueryLog();
            if (!empty($activityIds)) {
                return Menu::query()
                ->select('id','name','menulink','icon','menu_order')
                ->when(Arr::has($request,'name'), function ($query) use($request){
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function($query) use($request){
                    $query->where($request);
                })
                ->whereHas('SubMenu.MenuActivity', function($query) use ($activityIds){
                    if (!empty($activityIds)) {
                        $query->whereIn('activity_id', $activityIds);
                    }
                })
                ->with('SubMenu:id,menu_id,name,submenu_order,submenulink,icon','SubMenu.MenuActivity.Activity:id,name')
                ->orderBy($orderBy, $order)->paginate($rowsPerPage)
                ->appends(request()->query());
            }else{
                return [];
            }
            // dump(DB::getQueryLog());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Menus and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function findMenuActivity($request)
    {

        // $activityIds = $request['activity_id'];
        $activity = Activity::select('id')->whereIn('name',$request['activity'])->get();
        $activityIds = $activity->map(function ($activity) {
            return $activity->id;
        })->all();
        $request = $request->except(['activity']);
        try {
            // DB::enableQueryLog();
            if (!empty($activityIds)) {
                $dd= Menu::query()
                ->select('id','name','menulink','icon','menu_order')
                ->when(Arr::has($request,'name'), function ($query) use($request){
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function($query) use($request){
                    $query->where($request);
                })
                ->whereHas('SubMenu.MenuActivity', function($query) use ($activityIds){
                    if (!empty($activityIds)) {
                        $query->whereIn('activity_id', $activityIds);
                    }
                })
                ->with('SubMenu:id,menu_id,name,submenulink,icon,submenu_order','SubMenu.MenuActivity.Activity:id,name')
                ->get();
                // dd($dd);
                // ->appends(request()->query());;
                return $dd;
            }else{
                return [];
            }
            // dump(DB::getQueryLog());
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
            return Menu::getTableFields();
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
            return Menu::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param MenuRequest $data
     *
     * @return Array
     */
    public function add(MenuRequest $data)
    {
        //Create a new entry in db
        try {
            Menu::create([
                'name' => $data->name,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add a single record in the table
     *
     * @param MenuRequest $data
     *
     * @return Array
     */
    public function saveMenuOrder(Request $data)
    {
        // $menus = [
        //     'mainMenuList' => [
        //         [
        //             "mainMenu" => "Acl",
        //             "id" => "2",
        //             "order" => "1"
        //         ],
        //         [
        //             "mainMenu" => "Agri",
        //             "id" => "3",
        //             "order" => "2"
        //         ]
        //     ],
        //     "subMenuList" => [
        //         [
        //             "name" => "User List",
        //             "id" => 1,
        //             "order" => 1.1
        //         ],
        //         [
        //             "name" => "User Activity",
        //             "id" => 2,
        //             "order" => 1.2
        //         ],
        //         [
        //             "name" => "Commodity",
        //             "id" => 4,
        //             "order" => 2.1
        //         ],
        //         [
        //             "name" => "Variety",
        //             "id" => 5,
        //             "order" => 2.2
        //         ]
        //     ]
        // ];

        // Create a new entry in db
        try {
            $multiplied = collect($data)->map(function (array $dataArr, string $datakey) {
                if($datakey == "mainMenuList"){
                    //update menu order
                    collect($dataArr)->map(function (array $data, string $key) {
                        return Menu::where('id',$data['id'])->update(['menu_order'=>$data['order']]);
                    });
                }
                if($datakey == "subMenuList"){
                    //update submenu order
                    collect($dataArr)->map(function (array $data, string $key) {
                        return SubMenu::where('id',$data['id'])->update(['submenu_order'=>$data['order']]);
                    });
                }
            });
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
            return Menu::select('id','name')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param MenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, int $id)
    {
        try {
            $menu = Menu::find($id);
            $menu->name = $request->name;
            $menu->save();
            return $menu;
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
            Menu::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return Menu::whereIn("id", $ids)
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
                return Menu::whereIn("id", $ids)
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
                return Menu::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
