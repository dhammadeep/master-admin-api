<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCompany extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uapp_user_company';

    public $timestamps = FALSE;

}
