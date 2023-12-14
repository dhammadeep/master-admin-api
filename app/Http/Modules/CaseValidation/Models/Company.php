<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\CaseValidation\Models\DrkDoc;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_company';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'business_entity_type',
        'license_no',
        'license_issue_date',
        'license_expiry_date',
        'date_of_incorporation',
        'pan_no',
        'cin_no',
        'gstn',
        'website',
        'nationality',
        'constitution'
    ];

    /**
     * The user company that has many company doc
     */
    public function companyDoc()
    {
        return $this->belongsToMany(DrkDoc::class, 'uapp_company_doc','company_id', 'doc_id')->withPivot(['status', 'rejection_reason_id']);
    }
}
