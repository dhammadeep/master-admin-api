<?php

namespace App\Http\Masters\Gen\Responses\Table;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RejectionReasonTypeBulKInsertResponse extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "name" => "file",
            "label" => "Bulk Upload File",
            "type" => "bulkInsert",
            "downloadSampleFileUrl" => Storage::cloud()->url('dev/master-data/dummy/gen_rejection_reason_type.xlsx'),
            "bulkInsertStoreUrl" => "gen/rejection_reason_type/import",
            "downloadErrorFileUrl" => "",
            "validators" => [
                "required" => true,
                "maxFileSize" => "10240",
                "MIMEType" => [
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                ]
            ]
        ];
    }
}
