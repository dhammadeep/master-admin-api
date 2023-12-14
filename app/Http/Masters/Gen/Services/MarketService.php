<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Masters\Gen\Requests\MarketRequest;
use App\Http\Masters\Geo\Requests\LocationRequest;
use App\Http\Masters\Gen\Repositories\MarketRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Geo\Repositories\LocationRepository;
use App\Http\Masters\Gen\Responses\Lists\MarketListResponse;
use App\Http\Masters\Gen\Responses\MarketBulKInsertResponse;
use App\Http\Masters\Gen\Responses\Table\MarketTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class MarketService
{
    protected $repository,$locationRepository;

    /**
     * Constructor based dependency injection
     *
     * @param MarketRepository $repository
     *
     * @return void
     */
    public function __construct(MarketRepository $repository,LocationRepository $locationRepository)
    {
        $this->repository = $repository;
        $this->locationRepository = $locationRepository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return MarketTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
        //     $locationDropdownResponse = new LocationDropdownResponse();
        //     $locationDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findLocation());
        //     $locationDropdownResponse = $locationDropdownResponse->formFieldAtributes;
        //    return new MarketTableCollection($this->repository->find($request),$locationDropdownResponse);
        return new MarketTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Market in the DB
     *
     * @param MarketRequest $data
     *
     * @return Array
     */
    public function add(MarketRequest $data )
    {
        try {
            $locationData = collect(
                [
                    'address' => $data->address,
                    'pincode' => $data->pincode
                ]
            )->all();
            $locationrequest = new LocationRequest($locationData);
            $location_id = $this->locationRepository->add($locationrequest);
            $data->merge(['location_id' => $location_id]);
            return $this->repository->add($data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }  catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Render the edit view for the Market model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findMarketById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new MarketListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param MarketRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MarketRequest $request, int $id)
    {
        // Retrieve the Market from the database
       try {
            $market = $this->repository->findById($id);
            if ($market) {
                $location = $this->locationRepository->findById($market->location_id);
                $locationData = collect(
                    [
                        'address' => $request->address,
                        'pincode' => $request->pincode
                    ]
                )->all();
                $locationrequest = new LocationRequest($locationData);
                if($location){
                    $this->locationRepository->update($locationrequest, $market->location_id);
                }
                $request->merge(['location_id'=>$market->location_id]);
                return $this->repository->update($request, $id);
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Get the dynamic table columns
     *
     * @return array
     */
    public function getTableFields(): array
    {
        try {
            return $this->repository->getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            // $formResponse = $this->repository->getFormFields();
            // $locationDropdownResponse = new LocationDropdownResponse();
            // $locationDropdownResponse->formFieldAtributes['options'] = new DropdownTableCollection($this->dropdownRepository->findLocation());
            // $locationDropdownResponse = $locationDropdownResponse->formFieldAtributes;
            // $final = array_merge([$locationDropdownResponse],$formResponse);
            // return $final;
            return $this->repository->getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the bulk insert form elements and data
     *
     * @return array
     */
    public function getBulkInsertFormFields(): array
    {
        try {
            return collect(new MarketBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Market record to 'rejected'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateRejectStatus(array $id)
    {
        try {
            return $this->repository->updateStatusReject(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Market record to 'Active'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateFinalizeStatus(array $id)
    {
        try{
            return $this->repository->updateStatusFinalize(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Market record to 'Approved'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateApproveStatus(array $id)
    {
        try{
            return $this->repository->updateStatusApprove(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }
}
