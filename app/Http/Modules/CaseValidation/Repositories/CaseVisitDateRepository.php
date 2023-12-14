<?php

namespace App\Http\Modules\CaseValidation\Repositories;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\CaseValidation\Models\CaseVisitDate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\CaseValidation\Requests\CaseVisitDateReschedulingRequest;
use App\Http\Modules\CaseValidation\Repositories\RepoInterface\CaseVisitDateRepoInterface;

class CaseVisitDateRepository implements CaseVisitDateRepoInterface
{

    /**
     * Case Visit Date rescheduling.
     * @param CaseVisitDateReschedulingRequest $caseVisitDateReschedulingRequest
     * @return \Illuminate\Support\Collection
     */
    public function rescheduling(CaseVisitDateReschedulingRequest $caseVisitDateReschedulingRequest,$caseVisitId)
    {
        try {

                 $scheduledDates = explode(',',$caseVisitDateReschedulingRequest->rescheduledDates);
                // Step 1: Collect the existing dates (A)
                $existingDates = CaseVisitDate::where('case_visit_id', $caseVisitId)->pluck('scheduled_date')->toArray();

                // Step 2: Calculate (B) - existing + added
                $addedDates = array_diff($scheduledDates, $existingDates);

                // Step 3: Calculate (C) - added, deleted
                $deletedDates = array_diff($existingDates, $scheduledDates);

                // Step 4: Mark entries in C for deletion
                CaseVisitDate::where('case_visit_id', $caseVisitId)
                    ->whereIn('scheduled_date', $deletedDates)
                    ->delete();

                // Add newly selected dates
                foreach ($addedDates as $date) {
                    CaseVisitDate::create([
                        'case_visit_id' => $caseVisitId,
                        'scheduled_date' => Carbon::createFromFormat('Y-m-d', $date),
                    ]);

                }

            return Null;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
