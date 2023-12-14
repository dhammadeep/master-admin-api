<?php

namespace App\Http\Modules\Authentication\Repositories;
use Exception;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use App\Http\Modules\Authentication\Models\Permission as AuthPermission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\PermissionRequest;

class PermissionRepository
{
    public function createPermission(PermissionRequest $data)
    {
        return Permission::create(
            [
                'name' => $data->name,
            ]
        );
    }

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
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $orderBy = 'id';
        if (!empty($request->orderBy)) {
            $orderBy = $request->orderBy;
        }
        $request = $request->except(['page', 'size', 'order', 'orderBy']);
        try {
            return Permission::query()
                ->select('id','name','guard_name')
                ->when(Arr::has($request, 'name'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function ($query) use ($request) {
                    $query->where($request);
                })
                // ->with('Language:id,name', 'UserActivity.Activity:id,name')
                ->orderBy($orderBy, $order)->paginate($rowsPerPage)
                ->appends(request()->query());
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
            return AuthPermission::getFormFields();
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
            return Permission::select('id', 'name')
            // ->with('UserActivity.Activity:id,name')
            ->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(PermissionRequest $data, int $id)
    {
        try {
            $permission = Permission::find($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        if ($permission) {
            // update permission name
            $data = $permission->update($data->toArray());
        }
        return $data;
    }
}
