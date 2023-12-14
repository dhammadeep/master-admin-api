<?php

namespace App\Http\Modules\CaseValidation\Responses;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseStackDetailsResponse extends ResourceCollection
{

     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($data) {
                return [
                    'caseWarehouseId' => $data->case_warehouse_id,
                    'stackNo' => $data->stack_no,
                    'noOfPackage' => $data->no_of_package,
                    'packageUomId' => $data->package_uom_id,
                    'packageUom' => $data->packageUnitOfMeasurement->name,
                    'unitPackageSize' => $data->unit_package_size,
                    'unitPackageSizeUomId' => $data->unit_package_size_uom_id,
                    'unitPackageSizeUom' => $data->unitPackageSizeUnitOfMeasurement->name,
                ];
            })->all()
        ;
    }
}
