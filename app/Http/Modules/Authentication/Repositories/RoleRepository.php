<?php

namespace App\Http\Modules\Authentication\Repositories;

use App\Http\Modules\Authentication\Models\Activity;
use Exception;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\RoleRequest;
use App\Http\Modules\Authentication\Repositories\RepoInterface\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAll($request, int $rowsPerPage = 50)
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
            return Activity::query()
            ->select('id','name','guard_name','description')
            ->when(Arr::has($request, 'name'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request['name']}%");
            }, function ($query) use ($request) {
                $query->where($request);
            })
            ->orderBy($orderBy, $order)->paginate($rowsPerPage)
            ->appends(request()->query());

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(RoleRequest $request)
    {
        $role = Role::create(
            [
                'name' => $request->name,
                'description' => $request->description,
            ]
        )->id;
        return $role;
    }

    public function addPermissionsToRole($roleId, array $permissions)
    {
        $role = Role::findById($roleId);
        $data = $role->syncPermissions($permissions);
        return $data;
    }

    public function getRoleByID(int $id)
    {
        return Role::select('name')->find($id);
    }

    public function update(RoleRequest $data, int $id)
    {
        try {
            $role = Role::find($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        if ($role) {
            // update role name
            $data = $role->update($data->toArray());
        }
        return $data;
    }


    public function delete(int $id)
    {
        return Role::find($id)->delete();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return Activity::getFormFields();
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
            return Activity::select('id', 'name','description')
            ->with('Permission:id,name')
            ->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
