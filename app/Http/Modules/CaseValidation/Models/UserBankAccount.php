<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Modules\CaseValidation\Models\UserBankAccountDoc;

class UserBankAccount extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uapp_user_bank_account';

    public $timestamps = FALSE;

    // Define the composite primary key
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status'
    ];

    /**
     * The user bank account that has many user bank account doc
     */
    public function userBankAccountDoc()
    {
        return $this->belongsToMany(DrkDoc::class, 'uapp_user_bank_account_doc','bank_account_id', 'doc_id')->withPivot(['status', 'rejection_reason_id']);
    }

}
