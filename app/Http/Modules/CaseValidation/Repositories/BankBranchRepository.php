<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\BankBranchDetails;
use App\Http\Modules\CaseValidation\Requests\BankBranchRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\BankBranchRepoInterface;

class BankBranchRepository implements BankBranchRepoInterface
{

    /**
     * get cached bank branch details details by ifsc code
     *
     * @param string $ifsc
     * @return \Illuminate\Support\Collection
     */
    public function findByIfscCached(string $ifsc)
    {
       // Redis::flushDB();
        try {
            $cacheKey = 'bank-branch:' . $ifsc;

            if ($bankBranchDetails = Redis::get($cacheKey)) {
                return $bankBranchDetails = json_decode($bankBranchDetails);
            }

            return $this->findById($ifsc);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get farm details by farm id
     *
     * @param string $userId
     * @return \Illuminate\Support\Collection
     */
    public function findById(string $ifsc)
    {
        try {
            $bankBranchDetails = BankBranchDetails::where('ifsc',$ifsc)->first();

            $cacheKey = 'bank-branch:' . $ifsc;
            Redis::setex($cacheKey, 60*60*2, json_encode($bankBranchDetails));

            return $bankBranchDetails;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
