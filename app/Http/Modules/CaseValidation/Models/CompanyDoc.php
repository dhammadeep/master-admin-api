<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Http\Modules\CaseValidation\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyDoc extends Pivot
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uapp_company_doc';

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
     * The user company doc that belongs to company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }
}
