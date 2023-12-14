<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farm extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_farm';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'location_id',
        'name',
        'lessor',
        'lessees',
        'registration_no',
        'mutation_no',
        'sale_deed_no',
        'registration_date',
        'ownership_type',
        'documented_area_sqm',
        'leased_from_date',
        'leased_till_date',
        'survey_no'
    ];

    /**
     * The drk farm that has many farm doc
     */
    public function farmDoc()
    {
        return $this->belongsToMany(DrkDoc::class, 'drk_farm_doc', 'farm_id', 'doc_id')->withPivot(['status', 'rejection_reason_id']);
    }
}
