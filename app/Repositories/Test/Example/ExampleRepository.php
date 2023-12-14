<?php

namespace App\Repositories\Test\Example;

use Exception;
use App\Models\Test\Example;
use App\Http\Requests\Test\Example\ExampleRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Test\Example\RepoInterface\ExampleRepoInterface;

class ExampleRepository implements ExampleRepoInterface
{
    /**
     * Find Examples and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        $rowsPerPage = 2;
        try {
            return Example::query()
            ->select('ID','Name','AboutMe','Gender','CountryID','StateID','ProfilePhoto','Status')
            ->when(!empty($on) && count(explode(".", $on)) == 1, function ($query) use ($on, $search) {
                $query->where($on, 'like', "%{$search}%");
            })
            ->when(count(explode(".", $on)) > 1, function ($query) use ($on, $search) {
                $on = explode(".", $on);
                $model = $on[0];
                $on = $on[1];
                $query->whereHas($model, function ($query2) use ($on, $search) {
                    $query2->where($on, 'like', "%{$search}%");
                });
            })
           //->with('State:ID,Name')
            ->orderBy('id', 'desc')->paginate($rowsPerPage)
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
            return Example::getTableFields();
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
            return Example::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param ExampleRequest $data
     *
     * @return Array
     */
    public function add(ExampleRequest $data)
    {
        //Create a new entry in db
        try {
            Example::create([
                'Name' => $data->Name,
                'AboutMe' => $data->AboutMe,
                'Gender' => $data->Gender,
                'CountryID'=> $data->CountryID,
                'StateID'=> $data->StateID,
                'ProfilePhoto'=> $data->ProfilePhoto
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
            return Example::select('ID','Name','AboutMe','Gender','CountryID','StateID','ProfilePhoto')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            dd("DDD");
            dd($e);
            throw $e;
        } catch (Exception $e) {
            dump("Here");
            dd($e);
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ExampleRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ExampleRequest $request, int $id)
    {
        try {
            $example = Example::find($id);
            $example->Name = $request->Name;
            $example->save();
            return $example;
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
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
            return Example::whereIn("ID", $ids)
                ->update(['Status' => 'Rejected']);
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
                return Example::whereIn("ID", $ids)
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
                return Example::whereIn("ID", $ids)
                    ->update(['Status' => 'Approved']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
