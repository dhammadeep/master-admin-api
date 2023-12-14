<?php

namespace App\Http\Modules\CaseValidation\Models;

use App\Http\Modules\CaseValidation\Models\Farm;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmDoc extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_farm_doc';

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
     * The farm doc that belongs to farm
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
