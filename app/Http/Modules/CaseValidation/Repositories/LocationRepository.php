<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use App\Http\Modules\CaseValidation\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Requests\BasicKycFormRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\LocationRepoInterface;

class LocationRepository implements LocationRepoInterface
{

    /**
     * Create a new location entry in the database and return the location ID
     *
     * @param mixed $locationRequest
     * @param int $locationId
     * @return \Illuminate\Support\Collection
     */
    public function insertUpdateLocation(mixed $locationRequest,int $locationId)
    {
        //insert ot update location
        try {

            $location = Location::updateOrCreate(
                ['id' => $locationId],
                [
                    'address' => $locationRequest['address'],
                    'pincode' => $locationRequest['pincode']
                ]
            );

            return $location->id;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }
}
