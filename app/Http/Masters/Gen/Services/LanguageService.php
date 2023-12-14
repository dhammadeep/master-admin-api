<?php

namespace App\Http\Masters\Gen\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Language;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Masters\Gen\Requests\LanguageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Gen\Repositories\LanguageRepository;
use App\Http\Masters\Gen\Responses\LanguageBulKInsertResponse;
use App\Http\Masters\Gen\Responses\Lists\LanguageListResponse;
use App\Http\Masters\Gen\Responses\Table\LanguageTableCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LanguageService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param LanguageRepository $repository
     *
     * @return void
     */
    public function __construct(LanguageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return LanguageTableCollection
     */
    public function getAllPaginatedTableData($request)
    {
        // Return in the given API resource format
        try {
           return new LanguageTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates a new Language in the DB
     *
     * @param LanguageRequest $data
     *
     * @return Array
     */
    public function add(mixed $fileUrls, LanguageRequest $data)
    {
        try {
            if ($fileUrls) {
                //set image field
                $data->merge([
                    'logo' => $fileUrls['logo'],
                    'file_url' => $fileUrls['file_url']
                ]);
            }
            return $this->repository->add($fileUrls, $data);
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
            $pathFileUrl='';
            $path = '';
            if(!empty($request->file('logo'))){
                $path = $request->file('logo')->store('/dev/master-data/gen-language', 's3');
                $path = Storage::cloud()->url($path);
                $filePath_normal = '/dev/master-data/gen-language/normal/' . $request->file('logo')->hashName();
                $filePath_thumb = '/dev/master-data/gen-language/thumbnails/' . $request->file('logo')->hashName();
                $image_normal = Image::make($request->file('logo'))->widen(800, function ($constraint) {
                    $constraint->upsize();
                });
                $image_thumb = Image::make($request->file('logo'))->widen(100, function ($constraint) {
                    $constraint->upsize();
                });
                Storage::disk('s3')->put($filePath_normal, $image_normal->stream());
                Storage::disk('s3')->put($filePath_thumb, $image_thumb->stream());
            }
            if(!empty($request->file('file_url'))){
                $pathFileUrl = $request->file('file_url')->store('/dev/master-data/gen-language/file', 's3');
                $pathFileUrl = Storage::cloud()->url($pathFileUrl);
            }
            $urls = collect(['logo' => $path,'file_url' => $pathFileUrl]);
            return $urls;
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function S3ImageLogoDelete(int $id)
    {
        try {
            $language = $this->repository->findById($id);
            $imgUrl = parse_url($language->logo);
            Storage::disk('s3')->delete($imgUrl['path']);
            $normal = parse_url(Str::replace('/dev/master-data/gen-language/', '/dev/master-data/gen-language/normal/', $language->logo));
            Storage::disk('s3')->delete($normal['path']);
            $thumbnails = parse_url(Str::replace('/dev/master-data/gen-language/', '/dev/master-data/gen-language/thumbnails/', $language->logo));
            Storage::disk('s3')->delete($thumbnails['path']);
            return true;
            // return $request->file('Logo')->delete('images', 's3');
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function S3ImageFileUrlDelete(int $id)
    {
        try {
            $language = $this->repository->findById($id);
            $fileUrl = parse_url($language->file_url);
            Storage::disk('s3')->delete($fileUrl['path']);
            return true;
            // return $request->file('Logo')->delete('images', 's3');
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Language model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findLanguageById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new LanguageListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param LanguageRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LanguageRequest $request, mixed $fileUrls, int $id)
    {
        // Retrieve the Language from the database
       try {
            $language = $this->repository->findById($id);
            if ($fileUrls) {
                if(empty($fileUrls['file_url'])){
                    $fileUrls['file_url']= $language->file_url;
                }
                if(empty($fileUrls['logo'])){
                    $fileUrls['logo']= $language->logo;
                }
                //set image field
                $request->merge([
                    'logo' => $fileUrls['logo'],
                    'file_url' => $fileUrls['file_url']
                ]);
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
            return collect(new LanguageBulKInsertResponse([]))->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**

     * Update the status of an Language record to 'rejected'.
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
     * Update the status of an Language record to 'Active'.
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
     * Update the status of an Language record to 'Approved'.
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
