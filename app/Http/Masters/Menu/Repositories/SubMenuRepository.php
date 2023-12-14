<?php

namespace App\Http\Masters\Menu\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Menu\Models\SubMenu;
use App\Http\Masters\Menu\Requests\SubMenuRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Menu\Repositories\RepoInterface\SubMenuRepoInterface;

class SubMenuRepository implements SubMenuRepoInterface
{
    /**
     * Find SubMenus and get results in pagination
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
            return SubMenu::query()
            ->select('id','menu_id','name','submenulink','icon','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Menu:id,name')
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
            return SubMenu::getTableFields();
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
            return SubMenu::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param SubMenuRequest $data
     *
     * @return Array
     */
    public function add(SubMenuRequest $data)
    {
        //Create a new entry in db
        try {
            SubMenu::create([
                'menu_id' => $data->menu_id,
                'name' => $data->name,
                'submenulink' => $data->submenulink,
                'icon' => "<i class='fa-solid fa-circle'></i>"
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
            return SubMenu::select('id','menu_id','name','submenulink','icon')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param SubMenuRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SubMenuRequest $request, int $id)
    {
        try {
            $subMenu = SubMenu::find($id);
            $subMenu->menu_id = $request->menu_id;
            $subMenu->name = $request->name;
            $subMenu->submenulink = $request->submenulink;
            $subMenu->icon = "<i class='fa-solid fa-circle'></i>";
            $subMenu->save();
            return $subMenu;
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
            SubMenu::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return SubMenu::whereIn("id", $ids)
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
                return SubMenu::whereIn("id", $ids)
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
                return SubMenu::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
