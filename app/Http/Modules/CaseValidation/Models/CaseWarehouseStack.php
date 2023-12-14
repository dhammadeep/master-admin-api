<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Gen\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseWarehouseStack extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_case_warehouse_stack';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_warehouse_id',
        'stack_no',
        'no_of_package',
        'package_uom_id',
        'unit_package_size',
        'unit_package_size_uom_id'
    ];

    /**
     * Get the uom associated with the State
     * @return object
     */
    public function packageUnitOfMeasurement()
    {
       // return $this->belongsTo(UnitOfMeasurement::class);
        return $this->belongsTo(
            related: UnitOfMeasurement::class,
            foreignKey: 'package_uom_id',
            ownerKey: 'id'
        );
    }

    /**
     * Get the uom associated with the State
     * @return object
     */
    public function unitPackageSizeUnitOfMeasurement()
    {
       // return $this->belongsTo(UnitOfMeasurement::class);
        return $this->belongsTo(
            related: UnitOfMeasurement::class,
            foreignKey: 'unit_package_size_uom_id',
            ownerKey: 'id'
        );
    }

}
