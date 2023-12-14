<?php

namespace App\Http\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserVerification extends Model
{
    use HasFactory;

    protected $table = 'uapp_user_verification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'basic_kyc_status',
    ];

    public $timestamps = FALSE;

    public function user()
    {
        // Assuming a foreign key relationship with user_id
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
