<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDoc extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uapp_user_doc';

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
     * The user doc that belongs to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
