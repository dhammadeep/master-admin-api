<?php

namespace App\Http\Modules\CaseValidation\Services;

use Exception;
use Illuminate\Http\Request;
use App\Http\Modules\CaseValidation\Enums\FarmOwnershipType;
use App\Http\Modules\CaseValidation\Requests\CaseListRequest;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitFarmer;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitGeoPlot;
use App\Http\Modules\CaseValidation\Responses\Table\CaseListTableCollection;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitHarvestedGeoTag;
use App\Http\Modules\CaseValidation\Responses\ListFilterCropTypeDropdownCollection;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitWarehouseWithCompany;
use App\Http\Modules\CaseValidation\Responses\CaseListFilterCropTypeDropdownResponse;
use App\Http\Modules\CaseValidation\Repositories\CaseListInitialVisitFarmerRepository;
use App\Http\Modules\CaseValidation\Models\CaseListInitialVisitWarehouseWithoutCompany;
use App\Http\Modules\CaseValidation\Repositories\CaseListInitialVisitGeoPlotRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseListInitialVisitHarvestedGeoTagRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseListInitialVisitWarehouseWithCompanyRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseListInitialVisitWarehouseWithoutCompanyRepository;

class CaseListService
{
    protected $caseListInitialVisitFarmerRepository,$caseListInitialVisitWarehouseWithoutCompanyRepository,$caseListInitialVisitWarehouseWithCompanyRepository,$caseListInitialVisitGeoPlotRepository,$caseListInitialVisitHarvestedGeoTagRepository;

    /**
     * Constructor based dependency injection
     *
     *
     * @return void
     */
    public function __construct( CaseListInitialVisitFarmerRepository $caseListInitialVisitFarmerRepository, CaseListInitialVisitWarehouseWithoutCompanyRepository $caseListInitialVisitWarehouseWithoutCompanyRepository, CaseListInitialVisitWarehouseWithCompanyRepository $caseListInitialVisitWarehouseWithCompanyRepository, CaseListInitialVisitGeoPlotRepository $caseListInitialVisitGeoPlotRepository,CaseListInitialVisitHarvestedGeoTagRepository $caseListInitialVisitHarvestedGeoTagRepository)
    {
        $this->caseListInitialVisitFarmerRepository = $caseListInitialVisitFarmerRepository;
        $this->caseListInitialVisitWarehouseWithoutCompanyRepository = $caseListInitialVisitWarehouseWithoutCompanyRepository;
        $this->caseListInitialVisitWarehouseWithCompanyRepository = $caseListInitialVisitWarehouseWithCompanyRepository;
        $this->caseListInitialVisitGeoPlotRepository = $caseListInitialVisitGeoPlotRepository;
        $this->caseListInitialVisitHarvestedGeoTagRepository = $caseListInitialVisitHarvestedGeoTagRepository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getFarmList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitFarmerRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitFarmerRepository->getList($request, $rowsPerPage, $caseListOptions),CaseListInitialVisitFarmer::getTableFields(),CaseListInitialVisitFarmer::getFilterFields($croTypeDropdownResponse));
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
     * @return CaseListTableCollection
     */
    public function getWarehouseFarmerList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitWarehouseWithoutCompanyRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitWarehouseWithoutCompanyRepository->getList($request, $rowsPerPage, $caseListOptions),CaseListInitialVisitWarehouseWithoutCompany::getTableFields(),CaseListInitialVisitWarehouseWithoutCompany::getFilterFields($croTypeDropdownResponse));

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
     * @return CaseListTableCollection
     */
    public function getWarehouseCompanyList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitWarehouseWithCompanyRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitWarehouseWithCompanyRepository->getList($request, $rowsPerPage, $caseListOptions),CaseListInitialVisitWarehouseWithCompany::getTableFields(),CaseListInitialVisitWarehouseWithCompany::getFilterFields($croTypeDropdownResponse));

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
     * @return CaseListTableCollection
     */
    public function getGeoPlotList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitGeoPlotRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitGeoPlotRepository->getList($request, $rowsPerPage, $caseListOptions),CaseListInitialVisitGeoPlot::getTableFields(),CaseListInitialVisitGeoPlot::getFilterFields($croTypeDropdownResponse));

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
     * @return CaseListTableCollection
     */
    public function getHarvestedList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitHarvestedGeoTagRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitHarvestedGeoTagRepository->getList($request, $rowsPerPage, $caseListOptions),CaseListInitialVisitHarvestedGeoTag::getTableFields(),CaseListInitialVisitHarvestedGeoTag::getFilterFields($croTypeDropdownResponse));

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of owned farm records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getOwnedList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        $ownershipType = FarmOwnershipType::OWNED->value;

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitFarmerRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitFarmerRepository->getList($request, $rowsPerPage, $caseListOptions, $ownershipType),CaseListInitialVisitFarmer::getTableFields(),CaseListInitialVisitFarmer::getFilterFields($croTypeDropdownResponse));

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of leased farm records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CaseListTableCollection
     */
    public function getLeasedList(Request $request, CaseListRequest $caseListOptions)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        $ownershipType = FarmOwnershipType::LEASED->value;

        // Retrieve the paginated table data
        try {
            $croTypeDropdownResponse = new CaseListFilterCropTypeDropdownResponse();
            $croTypeDropdownResponse->formFieldAtributes['options'] = new ListFilterCropTypeDropdownCollection($this->caseListInitialVisitFarmerRepository->listCropType($caseListOptions));
            $croTypeDropdownResponse = $croTypeDropdownResponse->formFieldAtributes;

            return  new CaseListTableCollection($this->caseListInitialVisitFarmerRepository->getList($request, $rowsPerPage, $caseListOptions, $ownershipType),CaseListInitialVisitFarmer::getTableFields(),CaseListInitialVisitFarmer::getFilterFields($croTypeDropdownResponse));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
