<?php

namespace App\Http\Masters\Common\Services;

use Exception;
use App\Http\Masters\Common\Requests\DropdownRequest;
use App\Http\Masters\Common\Repositories\DropdownRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Common\Responses\Lists\DropdownListResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Masters\Common\Responses\Table\DropdownTableCollection;


class DropdownService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param DropdownRepository $repository
     *
     * @return void
     */
    public function __construct(DropdownRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllCountry()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findCountry());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllState(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findState($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllDistrict(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findDistrict($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllCity(int $districtId)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findCity($districtId));
        } catch (Exception $e) {
            throw $e;
        }
    }


     /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllRejectionReason(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findRejectionReason($id));
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllRejectionReasonType()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findRejectionReasonType());
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllLanguage()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findLanguage());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllUomType()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findUomType());
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllLocation(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findLocation($id));
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllCommodity(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findCommodity($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllVariety(int $id)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findVariety($id));
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllUomByUomType(int $id=null)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findUomByUomType($id));
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * Render the edit view for the Dropdown model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findDropdownById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new DropdownListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllActivity(int $id=null)
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findActivity($id));
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllPermission()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findPermission());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return DropdownTableCollection
     */
    public function getAllMenu()
    {
        // Return in the given API resource format
        try {
           return new DropdownTableCollection($this->repository->findMenu());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
