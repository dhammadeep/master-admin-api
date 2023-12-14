<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\CaseValidation\Models\DrkDoc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Modules\CaseValidation\Models\UserBankAccount;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBankAccountDoc extends Pivot
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uapp_user_bank_account_doc';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'rejection_reason_id'
    ];

    /**
     * The user bank account doc that belongs to user bank accounts
     */
    public function userBankAccount()
    {
        return $this->belongsTo(UserBankAccount::class);

    }
}
