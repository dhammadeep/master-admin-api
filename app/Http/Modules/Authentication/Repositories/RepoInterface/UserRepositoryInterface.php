<?php

namespace App\Http\Modules\Authentication\Repositories\RepoInterface;

use Illuminate\Http\Request;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;

interface UserRepositoryInterface
{
    public function add(Request $request);

    //BO: Added By Anjali
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $userId);
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $userId);
    //BO: Added By Anjali

}
