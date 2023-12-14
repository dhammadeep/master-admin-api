<?php

namespace App\Http\Masters\Common\Repositories;

use Exception;
use App\Http\Masters\Gen\Models\Bank;
use App\Http\Masters\Geo\Models\City;
use App\Http\Masters\Gen\Models\Stage;
use App\Http\Masters\Geo\Models\State;
use App\Http\Masters\Menu\Models\Menu;
use App\Http\Masters\Geo\Models\Country;
use App\Http\Masters\Agri\Models\Quality;
use App\Http\Masters\Agri\Models\Variety;
use App\Http\Masters\Gen\Models\Language;
use App\Http\Masters\Geo\Models\District;
use App\Http\Masters\Geo\Models\Location;
use App\Http\Masters\Agri\Models\Commodity;
use App\Http\Masters\Agri\Models\Parameter;
use App\Http\Masters\Agri\Models\Phenophase;
use App\Http\Masters\Gen\Models\RejectionReason;
use App\Http\Masters\Gen\Models\UnitOfMeasurement;
use App\Http\Masters\Gen\Models\RejectionReasonType;
use App\Http\Masters\Warehouse\Models\WarehouseType;
use App\Http\Modules\Authentication\Models\Activity;
use App\Http\Masters\Gen\Models\UnitOfMeasurementType;
use App\Http\Modules\Authentication\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Models\ActivityPermission;
use App\Http\Masters\Common\Repositories\RepoInterface\DropdownRepoInterface;

class DropdownRepository implements DropdownRepoInterface
{
    /**
     * Find Dropdowns and get results
     */
    public function findCountry()
    {
        try {
            return Country::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findState(int $id=null)
    {
        try {
            return State::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('country_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findDistrict(int $id=null)
    {
        try {
            return District::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('state_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Find Dropdowns and get results
     */
    public function findCity(int $districtId=null)
    {
        // dd($stateId,$districtId);
        try {
            return City::select('id', 'name')
            ->when(!empty($id), function ($query) use ($districtId) {
                $query->where('district_id',$districtId);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findRejectionReason(int $id=null)
    {
        try {
            return RejectionReason::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('rejection_reason_type_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findRejectionReasonType()
    {
        try {
            return RejectionReasonType::select('id', 'name')
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findLanguage()
    {
        try {
            return Language::select('id', 'name')
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findLocation(int $id=null)
    {
        try {
            return Location::whereNotNull('address')->select('id', 'address')
            ->when(!empty($id))
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findCommodity(int $id=null)
    {
        try {
            return Commodity::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                // $query->where('rejection_reason_type_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findPhenophase(int $id=null)
    {
        try {
            return Phenophase::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                // $query->where('rejection_reason_type_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findParameter(int $id=null)
    {
        try {
            return Parameter::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                // $query->where('rejection_reason_type_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findVariety(int $id=null)
    {
        try {
            return Variety::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('commodity_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findQuality(int $id=null)
    {
        try {
            return Quality::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('quality_band_id',$id);
            })
            ->orderBy('id', 'desc')->get();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Find Dropdowns and get results
     */
    public function findUomByUomType(int $id=null)
    {
        try {
            return UnitOfMeasurement::select('id', 'name')
            ->when(!empty($id), function ($query) use ($id) {
                $query->where('uom_type_id',$id);
            })
            ->orderBy('id', 'desc')->get();
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
            return State::select('id','name')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findBank()
    {
        try {
            return Bank::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findStage()
    {
        try {
            return Stage::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Find Dropdowns and get results
     */
    public function findUomType()
    {
        try {
            return UnitOfMeasurementType::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findActivity(int $id=null)
    {
        try {
            if(!empty($id)){
                return ActivityPermission::with('permission:id,name')
                ->when(!empty($id), function ($query) use ($id) {
                        $query->where('activity_id',$id);
                    })
                ->get();
            }
            return Activity::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findPermission()
    {
        try {
            return Permission::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findWarehouseType()
    {
        try {
            return WarehouseType::select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find Dropdowns and get results
     */
    public function findMenu()
    {
        try {
            return Menu::select('id', 'name')
            ->orderBy('id', 'asc')
            ->where('status','=','APPROVED')
            ->get();
        } catch (Exception $e) {
            throw $e;
        }
    }


}
