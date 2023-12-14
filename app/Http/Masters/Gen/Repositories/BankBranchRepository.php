<?php

namespace App\Http\Masters\Gen\Repositories;

use Exception;
use Illuminate\Support\Arr;
use App\Http\Masters\Gen\Models\BankBranch;
use App\Http\Masters\Gen\Requests\BankBranchRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Repositories\RepoInterface\BankBranchRepoInterface;

class BankBranchRepository implements BankBranchRepoInterface
{
    /**
     * Find BankBranches and get results in pagination
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
            return BankBranch::query()
            ->select('id','bank_id','name','ifsc','status')
            ->when(Arr::has($request,'name'), function ($query) use($request){
                $query->where('name', 'like', "%{$request['name']}%");
            }, function($query) use($request){
                $query->where($request);
            })
            ->with('Bank:id,name')
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
            return BankBranch::getTableFields();
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
            return BankBranch::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param BankBranchRequest $data
     *
     * @return Array
     */
    public function add(BankBranchRequest $data)
    {
        //Create a new entry in db
        try {
            BankBranch::create([
                'bank_id' => $data->bank_id,
                'name' => $data->name,
                'ifsc' => $data->ifsc
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
            return BankBranch::select('id','bank_id','name','ifsc')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param BankBranchRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BankBranchRequest $request, int $id)
    {
        try {
            $bankBranch = BankBranch::find($id);
            $bankBranch->name = $request->name;
            $bankBranch->bank_id = $request->bank_id;
            $bankBranch->ifsc = $request->ifsc;
            $bankBranch->save();
            return $bankBranch;
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
            BankBranch::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return BankBranch::whereIn("id", $ids)
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
                return BankBranch::whereIn("id", $ids)
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
                return BankBranch::whereIn("id", $ids)
                    ->update(['Status' => 'APPROVED']);
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
    public function updateStatus(array $id, string $status)
    {
        try {
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) use ($status){
                return BankBranch::whereIn("id", $ids)
                    ->update(['Status' => $status]);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
