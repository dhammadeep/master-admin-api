<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Modules\CaseValidation\Models\CaseWarehouse;

class WarehouseDoc extends Pivot
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_warehouse_doc';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'rejection_reason_id',
    ];

    /**
     * The warehouse doc that belongs to user bank accounts
     */
    public function caseWarehouse()
    {
        return $this->belongsTo(CaseWarehouse::class);

    }
}
