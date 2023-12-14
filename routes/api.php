<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Masters\Gen\Models\Stage;
use App\Http\Masters\Agri\Models\Parameter;
use App\Http\Masters\Gen\Controllers\BankController;
use App\Http\Masters\Geo\Controllers\CityController;
use App\Http\Modules\Gen\Controllers\MenuController;
use App\Http\Masters\Gen\Controllers\StageController;
use App\Http\Masters\Geo\Controllers\StateController;
use App\Http\Masters\Gen\Controllers\MarketController;
use App\Http\Masters\Geo\Controllers\RegionController;
use App\Http\Masters\Geo\Controllers\CountryController;
use App\Http\Modules\Cases\Controllers\CasesController;
use App\Http\Masters\Agri\Controllers\QualityController;
use App\Http\Masters\Agri\Controllers\VarietyController;
use App\Http\Masters\Gen\Controllers\LanguageController;
use App\Http\Masters\Geo\Controllers\DistrictController;
use App\Http\Masters\Menu\Controllers\SubMenuController;
use App\Http\Masters\Test\Controllers\ExampleController;
use App\Http\Masters\Agri\Controllers\CropTypeController;
use App\Http\Masters\Geo\Controllers\ContinentController;
use App\Http\Masters\Agri\Controllers\CommodityController;
use App\Http\Masters\Agri\Controllers\ParameterController;
use App\Http\Masters\Gen\Controllers\BankBranchController;
use App\Http\Masters\Gen\Controllers\StageVideoController;
use App\Http\Masters\Gen\Controllers\TenderTypeController;
use App\Http\Masters\Agri\Controllers\PhenophaseController;
use App\Http\Masters\Common\Controllers\DropdownController;
use App\Http\Masters\Menu\Controllers\ActivityMenuController;
use App\Http\Masters\Agri\Controllers\CommodityModelController;
use App\Http\Masters\Agri\Controllers\PlantpartColorController;
use App\Http\Masters\Gen\Controllers\RejectionReasonController;
use App\Http\Masters\warehouse\Controllers\warehouseController;
use App\Http\Modules\Authentication\Controllers\RoleController;
use App\Http\Modules\Authentication\Controllers\UserController;
use App\Http\Masters\Gen\Controllers\UnitOfMeasurementController;
use App\Http\Modules\CaseValidation\Controllers\GeoTagController;
use App\Http\Modules\CaseValidation\Controllers\LandDocController;
use App\Http\Masters\Agri\Controllers\CommodityParameterController;
use App\Http\Masters\Agri\Controllers\PhenophaseDurationController;
use App\Http\Masters\Gen\Controllers\RejectionReasonTypeController;
use App\Http\Masters\Warehouse\Controllers\WarehouseTypeController;
use App\Http\Masters\Gen\Controllers\UnitOfMeasurementTypeController;
use App\Http\Modules\Authentication\Controllers\PermissionController;
use App\Http\Modules\CaseValidation\Controllers\CompanyDocController;
use App\Http\Masters\Agri\Controllers\QualityParameterRangeController;
use App\Http\Modules\CaseValidation\Controllers\BasicKycDocController;
use App\Http\Modules\CaseValidation\Controllers\GeoPlottingController;
use App\Http\Modules\CaseValidation\Controllers\WarehouseDocController;
use App\Http\Modules\DocWidgetStructure\Controllers\DocWidgetController;
use App\Http\Modules\CaseValidation\Controllers\BankAccountDocController;
use App\Http\Modules\CaseValidation\Controllers\ScheduledVisitController;
use App\Http\Modules\IncompleteCase\Controllers\IncompleteCaseController;
use App\Http\Masters\Menu\Controllers\MenuController as MainMenuController;
use App\Http\Modules\Authentication\Controllers\ActivityPermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/register', [UserController::class, 'register']);
Route::post('/v1/login', [UserController::class, 'login']);
Route::post('/v1/logout', [UserController::class, 'logout']);
Route::post('refresh', [UserController::class, 'refresh']);
Route::get('me', [UserController::class, 'me'])->middleware('auth:api');

Route::post('/v1/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/v1/change-password', [UserController::class, 'changePassword']);

Route::middleware('auth:api')->group(function () {
    Route::get('/v1/user/{user}/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/v1/user/profileUpdate/{user}', [UserController::class, 'profileUpdate'])->name('user.profileUpdate');
    Route::put('/v1/user/changePassword/{user}', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::get('/v1/user/{user}/photo', [UserController::class, 'photo'])->name('user.photo');
    Route::put('/v1/user/photoUpload/{user}', [UserController::class, 'photoUpload'])->name('user.photoUpload');
});

Route::middleware('auth:api', 'permission')->group(function () {
    //user
    Route::resource('/v1/user', UserController::class);
    // Role routes
    Route::resource('/v1/roles', RoleController::class);
    Route::post('/v1/roles/{role_id}/permissions', [RoleController::class, 'addPermissionsToRole'])->name('RoleController.addPermissionsToRole');

    // Permission routes
    Route::resource('/v1/permissions', PermissionController::class);
    Route::resource('/v1/roles-permission', ActivityPermissionController::class);

    //Incomplete Case List route
    Route::get('/v1/case-list/incomplete-cases', [IncompleteCaseController::class, 'getList'])->name('IncompleteCaseController.getList');

    Route::resource('/test/example', ExampleController::class);
});

Route::resource('/v1/example', ExampleController::class);
Route::resource('/v1/geo/country', CountryController::class);
Route::resource('/v1/geo/state', StateController::class);
Route::resource('/v1/geo/district', DistrictController::class);
Route::resource('/v1/geo/city', CityController::class);
Route::resource('/v1/geo/region', RegionController::class);
Route::resource('/v1/geo/continent', ContinentController::class);
Route::resource('/v1/gen/rejection-reason-type', RejectionReasonTypeController::class);
Route::resource('/v1/gen/rejection-reason', RejectionReasonController::class);
Route::resource('/v1/gen/bank', BankController::class);
Route::resource('/v1/gen/bank-branch', BankBranchController::class);
Route::resource('/v1/gen/language', LanguageController::class);
Route::resource('/v1/gen/market', MarketController::class);
Route::resource('/v1/gen/tender-type', TenderTypeController::class);
Route::resource('/v1/gen/uom', UnitOfMeasurementController::class);
Route::resource('/v1/gen/uom-type', UnitOfMeasurementTypeController::class);
Route::resource('/v1/gen/stage', StageController::class);
Route::resource('/v1/gen/stage-video', StageVideoController::class);
Route::resource('/v1/agri/crop-type', CropTypeController::class);
Route::resource('/v1/agri/commodity', CommodityController::class);
Route::resource('/v1/agri/quality', QualityController::class);
Route::resource('/v1/agri/quality-parameter-range', QualityParameterRangeController::class);
Route::resource('/v1/agri/variety', VarietyController::class);
Route::resource('/v1/agri/parameter', ParameterController::class);
Route::resource('/v1/agri/commodity-parameter', CommodityParameterController::class);
Route::resource('/v1/agri/commodity-model', CommodityModelController::class);
Route::resource('/v1/agri/commodity-model', CommodityModelController::class);
Route::resource('/v1/agri/phenophase', PhenophaseController::class);
Route::resource('/v1/agri/phenophase-duration', PhenophaseDurationController::class);
Route::resource('/v1/agri/plantpart-color', PlantpartColorController::class);
Route::get('/v1/dropdown/country', [DropdownController::class, 'listCountry']);
Route::get('/v1/dropdown/language', [DropdownController::class, 'listLanguage']);
Route::get('/v1/geo/country/download/{file}', [CountryController::class, 'download']);
Route::get('/v1/dropdown/state/{countryId}', [DropdownController::class, 'listState']);
Route::get('/v1/dropdown/district/{stateId}', [DropdownController::class, 'listDistrict']);
Route::get('/v1/dropdown/city/{stateId}/{districtId}', [DropdownController::class, 'listCity']);
Route::get('/v1/dropdown/rejection-reason/{rejectionResonTypeId}', [DropdownController::class, 'listRejectionReason']);
Route::get('/v1/dropdown/variety/{commodityId}', [DropdownController::class, 'listVariety']);
Route::get('/v1/dropdown/uom/{uomTypeId?}', [DropdownController::class, 'listOfUomByUomType']);
Route::get('/v1/dropdown/uom-type', [DropdownController::class, 'listOfUomType']);
Route::get('/v1/dropdown/activity/{activityId?}', [DropdownController::class, 'listAcivity']);
Route::get('/v1/dropdown/permission', [DropdownController::class, 'listPermission']);
Route::get('/v1/gen/menu', [MenuController::class, 'index']);
Route::post('/v1/gen/menu-activity', [MenuController::class, 'getMenuWithActivity']);
Route::post('/v1/menu/order', [MenuController::class, 'saveMenuWithOrder']);
Route::middleware(['table'])->group(function () {
    Route::post('/v1/geo/country/import', [CountryController::class, 'import']);
    Route::post('/v1/geo/state/import', [StateController::class, 'import']);
    Route::post('/v1/geo/district/import', [DistrictController::class, 'import']);
    Route::post('/v1/geo/city/import', [CityController::class, 'import']);
    Route::post('/v1/geo/region/import', [RegionController::class, 'import']);
    Route::post('/v1/geo/continent/import', [ContinentController::class, 'import']);
    Route::post('/v1/gen/rejection-reason-type/import', [RejectionReasonTypeController::class, 'import']);
    Route::post('/v1/gen/rejection-reason/import', [RejectionReasonController::class, 'import']);
    Route::post('/v1/gen/bank/import', [BankController::class, 'import']);
    Route::post('/v1/gen/bank-branch/import', [BankBranchController::class, 'import']);
    Route::post('/v1/gen/language/import', [LanguageController::class, 'import']);
    Route::post('/v1/gen/market/import', [MarketController::class, 'import']);
    Route::post('/v1/gen/tender-type/import', [TenderTypeController::class, 'import']);
    Route::post('/v1/gen/uom/import', [UnitOfMeasurementController::class, 'import']);
    Route::post('/v1/gen/uom-type/import', [UnitOfMeasurementTypeController::class, 'import']);
    Route::post('/v1/agri/crop-type/import', [CropTypeController::class, 'import']);
    Route::post('/v1/agri/commodity/import', [CommodityController::class, 'import']);
    Route::post('/v1/agri/quality/import', [QualityController::class, 'import']);
    Route::post('/v1/agri/variety/import', [VarietyController::class, 'import']);
    Route::post('/v1/agri/phenophase/import', [PhenophaseController::class, 'import']);
    Route::post('/v1/agri/phenophase-duration/import', [PhenophaseDurationController::class, 'import']);
    Route::post('/v1/agri/parameter/import', [ParameterController::class, 'import']);
    Route::post('/v1/agri/commodity-parameter/import', [CommodityParameterController::class, 'import']);
    Route::post('/v1/agri/commodity-model/import', [CommodityModelController::class, 'import']);
    Route::post('/v1/agri/plantpart-color/import', [PlantpartColorController::class, 'import']);
});
Route::get('/v1/geo/country/download/{file}', [CountryController::class, 'download']);
Route::get('/v1/geo/state/download/{file}', [StateController::class, 'download']);
Route::get('/v1/geo/district/download/{file}', [DistrictController::class, 'download']);
Route::get('/v1/geo/city/download/{file}', [CityController::class, 'download']);
Route::get('/v1/geo/region/download/{file}', [RegionController::class, 'download']);
Route::get('/v1/geo/continent/download/{file}', [ContinentController::class, 'download']);
Route::get('/v1/gen/rejection-reason-type/download/{file}', [RejectionReasonTypeController::class, 'download']);
Route::get('/v1/gen/rejection-reason/download/{file}', [RejectionReasonController::class, 'download']);
Route::get('/v1/gen/bank/download/{file}', [BankController::class, 'download']);
Route::post('/v1/gen/bank/update-status/{status}', [BankController::class, 'updateStatus']);
Route::post('/v1/gen/bank-branch/update-status/{status}', [BankBranchController::class, 'updateStatus']);
Route::get('/v1/gen/bank-branch/download/{file}', [BankBranchController::class, 'download']);
Route::get('/v1/gen/language/download/{file}', [LanguageController::class, 'download']);
Route::get('/v1/gen/market/download/{file}', [MarketController::class, 'download']);
Route::get('/v1/gen/tender-type/download/{file}', [TenderTypeController::class, 'download']);
Route::get('/v1/gen/uom/download/{file}', [UnitOfMeasurementController::class, 'download']);
Route::get('/v1/gen/uom-type/download/{file}', [UnitOfMeasurementTypeController::class, 'download']);
Route::get('/v1/agri/crop-type/download/{file}', [CropTypeController::class, 'download']);
Route::get('/v1/agri/commodity/download/{file}', [CommodityController::class, 'download']);
Route::get('/v1/agri/quality/download/{file}', [QualityController::class, 'download']);
Route::get('/v1/agri/variety/download/{file}', [VarietyController::class, 'download']);
Route::get('/v1/agri/phenophase/download/{file}', [PhenophaseController::class, 'download']);
Route::get('/v1/agri/phenophase-duration/download/{file}', [PhenophaseDurationController::class, 'download']);
Route::get('/v1/agri/parameter/download/{file}', [ParameterController::class, 'download']);
Route::get('/v1/warehouse/get-pending-list', [warehouseController::class, 'getPendingList']);
Route::resource('/v1/warehouse/get-approved-list', warehouseController::class);
Route::resource('/v1/warehouse/warehouse-type', WarehouseTypeController::class);
Route::resource('/v1/menu/menu', MainMenuController::class);
Route::resource('/v1/menu/sub-menu', SubMenuController::class);
Route::resource('/v1/menu/activity-menu', ActivityMenuController::class);
Route::resource('test/example', ExampleController::class);
Route::resource('geo/country', CountryController::class);

//case brief details
Route::middleware('auth:api')->get('/v1/cases/{caseId}/get-brief-details', [CasesController::class, 'getBriefDetails'])->name('CasesController.getBriefDetails');

//BO: Added By Anjali
Route::middleware('auth:api', 'permission')->group(function () {

    //Doc Widget Structure
    Route::post('/v1/doc-widget-structure', [DocWidgetController::class, 'getWidgetStructure']);

    //basic KYC doc validation
    Route::get('/v1/basic-kyc-doc-validation/get-farm-list', [BasicKycDocController::class, 'getFarmList'])->name('BasicKycDocController.getFarmList');
    Route::get('/v1/basic-kyc-doc-validation/get-warehouse-farmer-list', [BasicKycDocController::class, 'getWarehouseFarmerList'])->name('BasicKycDocController.getWarehouseFarmerList');
    Route::get('/v1/basic-kyc-doc-validation/get-warehouse-company-list', [BasicKycDocController::class, 'getWarehouseCompanyList'])->name('BasicKycDocController.getWarehouseCompanyList');
    Route::get('/v1/{caseId}/basic-kyc-doc-validation/get-details', [BasicKycDocController::class, 'getDetails'])->name('BasicKycDocController.getDetails');
    Route::patch('/v1/{caseId}/basic-kyc-doc-validation/store-details', [BasicKycDocController::class, 'storeDetails'])->name('BasicKycDocController.storeDetails');
    Route::patch('/v1/{caseId}/basic-kyc-doc-validation/reject-document', [BasicKycDocController::class, 'rejectDocument'])->name('BasicKycDocController.rejectDocument');
    Route::patch('/v1/{caseId}/basic-kyc-doc-validation/approve-document', [BasicKycDocController::class, 'approveDocument'])->name('BasicKycDocController.approveDocument');

    //land doc validation
    Route::get('/v1/land-doc-validation/get-list', [LandDocController::class, 'getList'])->name('LandDocController.getList');
    Route::get('/v1/land-doc-validation/get-owned-list', [LandDocController::class, 'getOwnedList'])->name('LandDocController.getOwnedList');
    Route::get('/v1/land-doc-validation/get-leased-list', [LandDocController::class, 'getLeasedList'])->name('LandDocController.getLeasedList');
    Route::get('/v1/{caseId}/land-doc-validation/get-details', [LandDocController::class, 'getDetails'])->name('LandDocController.getDetails');
    Route::patch('/v1/{caseId}/land-doc-validation/store-details', [LandDocController::class, 'storeDetails'])->name('LandDocController.storeDetails');
    Route::patch('/v1/{caseId}/land-doc-validation/reject-document', [LandDocController::class, 'rejectDocument'])->name('LandDocController.rejectDocument');
    Route::patch('/v1/{caseId}/land-doc-validation/approve-document', [LandDocController::class, 'approveDocument'])->name('LandDocController.approveDocument');

    //company doc validation
    Route::get('/v1/company-doc-validation/get-list', [CompanyDocController::class, 'getList'])->name('CompanyDocController.getList');
    Route::get('/v1/{caseId}/company-doc-validation/get-details', [CompanyDocController::class, 'getDetails'])->name('CompanyDocController.getDetails');
    Route::patch('/v1/{caseId}/company-doc-validation/store-details', [CompanyDocController::class, 'storeDetails'])->name('CompanyDocController.storeDetails');
    Route::patch('/v1/{caseId}/company-doc-validation/reject-document', [CompanyDocController::class, 'rejectDocument'])->name('CompanyDocController.rejectDocument');
    Route::patch('/v1/{caseId}/company-doc-validation/approve-document', [CompanyDocController::class, 'approveDocument'])->name('CompanyDocController.approveDocument');

    //warehouse doc validation
    Route::get('/v1/warehouse-doc-validation/get-warehouse-farmer-list', [WarehouseDocController::class, 'getWarehouseFarmerList'])->name('WarehouseDocController.getWarehouseFarmerList');
    Route::get('/v1/warehouse-doc-validation/get-warehouse-company-list', [WarehouseDocController::class, 'getWarehouseCompanyList'])->name('WarehouseDocController.getWarehouseCompanyList');
    Route::get('/v1/{caseId}/warehouse-doc-validation/get-details', [WarehouseDocController::class, 'getDetails'])->name('WarehouseDocController.getDetails');
    Route::patch('/v1/{caseId}/warehouse-doc-validation/store-details', [WarehouseDocController::class, 'storeDetails'])->name('WarehouseDocController.storeDetails');
    Route::patch('/v1/{caseId}/warehouse-doc-validation/store-stacks', [WarehouseDocController::class, 'storeStackDetails'])->name('WarehouseDocController.storeStackDetails');
    Route::patch('/v1/{caseId}/warehouse-doc-validation/reject-document', [WarehouseDocController::class, 'rejectDocument'])->name('WarehouseDocController.rejectDocument');
    Route::patch('/v1/{caseId}/warehouse-doc-validation/approve-document', [WarehouseDocController::class, 'approveDocument'])->name('WarehouseDocController.approveDocument');

    //bank account doc validation
    Route::get('/v1/bank-account-doc-validation/get-farm-list', [BankAccountDocController::class, 'getFarmList'])->name('BankAccountDocController.getFarmList');
    Route::get('/v1/bank-account-doc-validation/get-warehouse-farmer-list', [BankAccountDocController::class, 'getWarehouseFarmerList'])->name('BankAccountDocController.getWarehouseFarmerList');
    Route::get('/v1/bank-account-doc-validation/get-warehouse-company-list', [BankAccountDocController::class, 'getWarehouseCompanyList'])->name('BankAccountDocController.getWarehouseCompanyList');
    Route::get('/v1/{caseId}/bank-account-doc-validation/get-details', [BankAccountDocController::class, 'getDetails'])->name('BankAccountDocController.getDetails');
    Route::patch('/v1/{caseId}/bank-account-doc-validation/store-details', [BankAccountDocController::class, 'storeDetails'])->name('BankAccountDocController.storeDetails');
    Route::post('/v1/bank-account-doc-validation/get-bank-branch-details-by-ifsc', [BankAccountDocController::class, 'getBankBranchDetailsByIfsc'])->name('BankAccountDocController.getBankBranchDetailsByIfsc');
    Route::patch('/v1/{caseId}/bank-account-doc-validation/reject-document', [BankAccountDocController::class, 'rejectDocument'])->name('BankAccountDocController.rejectDocument');
    Route::patch('/v1/{caseId}/bank-account-doc-validation/approve-document', [BankAccountDocController::class, 'approveDocument'])->name('BankAccountDocController.approveDocument');

    //geo plotting validation
    Route::get('/v1/geo-plotting-validation/get-list', [GeoPlottingController::class, 'getList'])->name('GeoPlottingController.getList');
    Route::get('/v1/{caseId}/geo-plotting-validation/get-details', [GeoPlottingController::class, 'getDetails'])->name('GeoPlottingController.getDetails');
    Route::patch('/v1/{caseId}/geo-plotting-validation/reject', [GeoPlottingController::class, 'geoPlotReject'])->name('GeoPlottingController.geoPlotReject');
    Route::patch('/v1/{caseId}/geo-plotting-validation/approve', [GeoPlottingController::class, 'geoPlotApprove'])->name('GeoPlottingController.geoPlotApprove');

    //geo tag validation
    Route::get('/v1/geo-tag-validation/get-warehouse-farmer-list', [GeoTagController::class, 'getWarehouseFarmerList'])->name('GeoTagController.getWarehouseFarmerList');
    Route::get('/v1/geo-tag-validation/get-warehouse-company-list', [GeoTagController::class, 'getWarehouseCompanyList'])->name('GeoTagController.getWarehouseCompanyList');
    Route::get('/v1/geo-tag-validation/get-harvested-list', [GeoTagController::class, 'getHarvestedList'])->name('GeoTagController.getHarvestedList');
    Route::get('/v1/{caseId}/geo-tag-validation/get-details', [GeoTagController::class, 'getDetails'])->name('GeoTagController.getDetails');
    Route::patch('/v1/{caseId}/geo-tag-validation/reject', [GeoTagController::class, 'geoTagReject'])->name('GeoTagController.geoTagReject');
    Route::patch('/v1/{caseId}/geo-tag-validation/approve', [GeoTagController::class, 'geoTagApprove'])->name('GeoTagController.geoTagApprove');

    //case rescheduling
    Route::get('/v1/scheduled-visit/get-farm-list', [ScheduledVisitController::class, 'getFarmList'])->name('ScheduledVisitController.getFarmList');
    Route::get('/v1/scheduled-visit/get-warehouse-farmer-list', [ScheduledVisitController::class, 'getWarehouseFarmerList'])->name('ScheduledVisitController.getWarehouseFarmerList');
    Route::get('/v1/scheduled-visit/get-warehouse-company-list', [ScheduledVisitController::class, 'getWarehouseCompanyList'])->name('ScheduledVisitController.getWarehouseCompanyList');
    Route::get('/v1/{caseId}/scheduled-visit/get-details', [ScheduledVisitController::class, 'getDetails'])->name('ScheduledVisitController.getDetails');
    Route::patch('/v1/{caseId}/scheduled-visit/rescheduling', [ScheduledVisitController::class, 'rescheduling'])->name('ScheduledVisitController.rescheduling');

    //Incomplete Case List route
    Route::get('/v1/incomplete-cases/get-pending-list', [IncompleteCaseController::class, 'getPendingList'])->name('IncompleteCaseController.getPendingList');
    Route::get('/v1/incomplete-cases/get-unpaid-list', [IncompleteCaseController::class, 'getUnpaidList'])->name('IncompleteCaseController.getUnpaidList');
});
//EO: Added By Anjali
