<?php

namespace App\Http\Masters\Geo\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ContinentBulKInsertResponse extends JsonResource
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
            "downloadSampleFileUrl" => Storage::cloud()->url('dev/master-data/dummy/geo_continent.xlsx'),
            "bulkInsertStoreUrl" => "geo/continent/import",
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
