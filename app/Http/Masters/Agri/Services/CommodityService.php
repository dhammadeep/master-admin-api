<?php

namespace App\Http\Masters\Agri\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Masters\Agri\Requests\CommodityRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Agri\Repositories\CommodityRepository;
use App\Http\Masters\Agri\Responses\CommodityBulKInsertResponse;
use App\Http\Masters\Agri\Responses\Lists\CommodityListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Masters\Agri\Responses\Table\CommodityTableCollection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class CommodityService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param CommodityRepository $repository
     *
     * @return void
     */
    public function __construct(CommodityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CommodityTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new CommodityTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Commodity in the DB
     *
     * @param CommodityRequest $data
     *
     * @return Array
     */
    public function add(mixed $fileUrl, CommodityRequest $data)
    {
        try {
            if ($fileUrl) {
                //set image field
                $data->merge([
                    'logo' => $fileUrl,
                ]);
            }
            return $this->repository->add($fileUrl, $data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Commodity in the DB
     *
     * @param Request $data
     *
     * @return Array
     */
    public function S3ImageUpload(Request $request)
    {
        try {
            if(!empty($request->file('logo'))){
                $path = $request->file('logo')->store('/dev/master-data/agri-commodity', 's3');
                $filePath_normal = '/dev/master-data/agri-commodity/normal/' . $request->file('logo')->hashName();
                $filePath_thumb = '/dev/master-data/agri-commodity/thumbnails/' . $request->file('logo')->hashName();
                $image_normal = Image::make($request->file('logo'))->widen(800, function ($constraint) {
                    $constraint->upsize();
                });
                $image_thumb = Image::make($request->file('logo'))->widen(100, function ($constraint) {
                    $constraint->upsize();
                });
                Storage::disk('s3')->put($filePath_normal, $image_normal->stream());
                Storage::disk('s3')->put($filePath_thumb, $image_thumb->stream());
                return Storage::cloud()->url($path);
            }
            return;
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function S3ImageDelete(int $id)
    {
        try {
            $Commodity = $this->repository->findById($id);
            $imgUrl = parse_url($Commodity->logo);
            Storage::disk('s3')->delete($imgUrl['path']);
            $normal = parse_url(Str::replace('/dev/master-data/agri-commodity/', '/dev/master-data/agri-commodity/normal/', $Commodity->logo));
            Storage::disk('s3')->delete($normal['path']);
            $thumbnails = parse_url(Str::replace('/dev/master-data/agri-commodity/', '/dev/master-data/agri-commodity/thumbnails/', $Commodity->logo));
            Storage::disk('s3')->delete($thumbnails['path']);
            return true;
            // return $request->file('Logo')->delete('images', 's3');
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Commodity model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findCommodityById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new CommodityListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CommodityRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommodityRequest $request, $fileUrl, int $id)
    {
        // Retrieve the Commodity from the database
       try {
        $Commodity = $this->repository->findById($id);
        if ($fileUrl) {
            $request->merge([
                'logo' => $fileUrl
            ]);
        }
        if ($Commodity) {
            return $this->repository->update($request, $id);
        }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
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
            return collect(new CommodityBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Commodity record to 'rejected'.
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
     * Update the status of an Commodity record to 'Active'.
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
     * Update the status of an Commodity record to 'Approved'.
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
