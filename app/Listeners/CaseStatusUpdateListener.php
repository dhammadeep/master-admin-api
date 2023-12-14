<?php

namespace App\Listeners;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Events\CaseStatusUpdateEvent;
use App\Http\Modules\Cases\Models\Cases;
use App\Http\Modules\Authentication\Models\User;
use App\Http\Modules\CaseValidation\Models\Farm;
use App\Http\Modules\CaseValidation\Models\GeoTag;
use App\Http\Modules\CaseValidation\Models\Company;
use App\Http\Modules\CaseValidation\Models\GeoPlot;
use App\Http\Modules\Cases\Repositories\CasesRepository;
use App\Http\Modules\CaseValidation\Models\CaseWarehouse;
use App\Http\Modules\CaseValidation\Models\UserBankAccount;
use App\Http\Modules\Authentication\Models\UserVerification;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Repositories\CaseFarmRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoTagRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseGeoPlotRepository;
use App\Http\Modules\CaseValidation\Repositories\UserCompanyRepository;
use App\Http\Modules\CaseValidation\Repositories\CaseWarehouseRepository;
use App\Http\Modules\CaseValidation\Repositories\UserBankAccountRepository;

class CaseStatusUpdateListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CaseStatusUpdateEvent $event)
    {
        $caseId = $event->caseId;
        $status = $event->status;
        // Call the stored procedure to get widget, required_placeholders, uploaded_placeholders, and status field
        $result = DB::select(
            "CALL GetCaseDocUploadStatus($caseId)"
        );

        $entityStatusArr = [];

        $userStatus = $farmStatus = $warehouseStatus = $companyStatus = $bankStatus = $geoTagStatus = $geoPlotStatus = 0;

        // Assuming that $result is an array with the retrieved data
        if (!empty($result)) {
            $casesRepository = new CasesRepository();
            $caseDetails = $casesRepository->findByIdCached($caseId);

            $caseUserBankBranchRepository = new UserBankAccountRepository();
            $caseUserBankBranch = $caseUserBankBranchRepository->findByBankAccountIdCached($caseDetails->bank_account_id);

            if (in_array($caseDetails->crop_type_id, [1, 2, 3])) {
                $caseFarmRepository = new CaseFarmRepository();
                $caseFarm = $caseFarmRepository->findByCaseIdCached($caseId);
            }

            if (in_array($caseDetails->crop_type_id, [4])) {
                $caseWarehouseRepository = new CaseWarehouseRepository();
                $caseWarehouse = $caseWarehouseRepository->findByCaseIdCached($caseId);
                if ($caseDetails->is_trader == 1) {
                    $caseCompanyRepository = new UserCompanyRepository();
                    $caseCompany = $caseCompanyRepository->findByUserIdCached($caseDetails->user_id);
                }
            }

            //geoplot
            if (in_array($caseDetails->crop_type_id, [1, 2])) {
                $caseGeoPlotRepository = new CaseGeoPlotRepository();
                $caseGeoPlot = $caseGeoPlotRepository->findByCaseIdCached($caseId);

                if (!empty($caseGeoPlot)) {
                    $geoPlot = GeoPlot::where('id', $caseGeoPlot->geo_plot_id)
                       // ->where('status', $status)
                        ->first();

                    if (!empty($geoPlot)) {
                        $entityStatusArr[] = $geoPlot->status;
                        $geoPlotStatus = 1;
                    }
                }
            }

            //geotag
            if (in_array($caseDetails->crop_type_id, [3, 4])) {
                $caseGeoTagRepository = new CaseGeoTagRepository();
                $caseGeoTag = $caseGeoTagRepository->findByCaseIdCached($caseId);
                if (empty($caseGeoTag)) {
                    $geoTag = GeoTag::where('id', $caseGeoTag->geo_tag_id)
                        ->first();

                    if (!empty($geoTag)) {
                        $entityStatusArr[] = $geoTag->status;
                        $geoTagStatus = 1;
                    }
                }
            }
            foreach ($result as $data) {

                $widgetId = $data->widget_id;
                $toBeStatusForParents = $data->to_be_status_for_parents;
                $cropType = $caseDetails->crop_type_id;

                if ($widgetId == 1 && $toBeStatusForParents) {
                    // Update status in User entity
                    User::where('id', $caseDetails->user_id)->update(['status' => $toBeStatusForParents]);
                    UserVerification::where('user_id', $caseDetails->user_id)->update(['basic_kyc_status' => $toBeStatusForParents]);

                    $entityStatusArr[] = $toBeStatusForParents;
                    $userStatus = 1;
                } elseif ($widgetId == 5 && $toBeStatusForParents) {
                    // Update status in UserBankAccount entity
                    if ($caseUserBankBranch) {

                        UserBankAccount::where('id', $caseUserBankBranch->id)->update(['status' => $toBeStatusForParents]);

                        $entityStatusArr[] = $toBeStatusForParents;
                        $bankStatus = 1;

                    }
                }

                // Check conditions based on cropType and update status in the corresponding entity
                if ($cropType == 1 || $cropType == 2 || $cropType == 3) {
                    if ($widgetId == 3 && $toBeStatusForParents) {
                        // Update status in Farm entity
                        if ($caseFarm) {
                            Farm::where('id', $caseFarm->farm_id)->update(['status' => $toBeStatusForParents]);
                            $entityStatusArr[] = $toBeStatusForParents;
                            $farmStatus = 1;
                        }
                    }
                }

                if ($cropType == 4) {
                    if ($caseDetails->is_trader == 1) {

                        if ($widgetId == 2 && $toBeStatusForParents) {
                            // Update status in company entity
                            if ($caseCompany) {
                                Company::where('id', $caseCompany->company_id)->update(['status' => $toBeStatusForParents]);
                                $entityStatusArr[] = $toBeStatusForParents;
                                $companyStatus = 1;
                            }
                        }
                    }
                    if ($widgetId == 4 && $toBeStatusForParents) {
                        // Update status in Warehouse entity
                        if ($caseWarehouse) {
                            CaseWarehouse::where('id', $caseWarehouse->id)->update(['status' => $toBeStatusForParents]);
                            $entityStatusArr[] = $toBeStatusForParents;
                            $warehouseStatus = 1;
                        }
                    }
                }
            }

            //if entityStatusArr the update case status to min value of entityStatusArr
            if(isset($entityStatusArr) && !empty($entityStatusArr)){
                $status = min($entityStatusArr);
                if($status == CaseValidationStatusEnum::VALIDATION_DONE->value){
                    try {
                        Cases::where('id', $caseId)->update(['status' => $status]);
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            }
            // Check if all required entities have 'VALIDATED_DONE' status, then update the case status
           /* if (in_array($caseDetails->crop_type_id, [1, 2, 3])) {
                // Update the case status to 'VALIDATED_DONE'
                if (in_array($caseDetails->crop_type_id, [1, 2]) && $geoPlotStatus == 1 && $userStatus == 1 && $farmStatus == 1 && $bankStatus == 1) {
                    try {
                        Cases::where('id', $caseId)->update(['status' => $status]);
                    } catch (Exception $e) {
                        throw $e;
                    }
                } elseif (in_array($caseDetails->crop_type_id, [3]) && $geoTagStatus == 1 && $userStatus == 1 && $farmStatus == 1 && $bankStatus == 1) {
                    try {
                        Cases::where('id', $caseId)->update(['status' => $status]);
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            } elseif (in_array($caseDetails->crop_type_id, [4])) {
                if ($caseDetails->is_trader == 1 && $geoTagStatus == 1 && $userStatus == 1 && $warehouseStatus == 1 && $companyStatus == 1 && $bankStatus == 1)
                    // Update the case status to 'VALIDATED_DONE'

                    try {
                        Cases::where('id', $caseId)->update(['status' => $status]);
                    } catch (Exception $e) {
                        throw $e;
                    }
            }*/
        }
    }
}
