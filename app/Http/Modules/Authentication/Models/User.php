<?php

namespace App\Http\Modules\Authentication\Models;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Http\Masters\Gen\Models\Language;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Http\Modules\CaseValidation\Models\DrkDoc;
use App\Http\Modules\CaseValidation\Models\UserKyc;
use App\Http\Modules\Gen\Repositories\MenuRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Modules\Authentication\Models\UserActivity;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param MenuRepository $repository
     *
     * @return void
     */
    // public function __construct(MenuRepository $repository)
    // {
    //     $this->repository = $repository;
    //     dd($this->repository);
    // }

    protected $table = 'uapp_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        'mobile',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public $timestamps = false;

    /**
     * Structure of dynamic form elements
     */
    protected static $formFields = [
        [
            'name' => 'mobile',
            'label' => 'Mobile',
            'type' => 'mobileNo',
            'validators' => [
                'required' => true,
                'maxLength' => 10,
                'minLength' => 10,
                'mobile' => true
            ],
            'value' => ''
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'validators' => [
                'required' => true,
                'maxLength' => 145,
                'minLength' => 10,
                'email' => true
            ],
            'value' => ''
        ],
        [
            'name' => 'language_id',
            'label' => 'Language',
            'type' => 'select',
            'validators' => [
                'required' => false
            ],
            'value' => ''
        ],
        [
            'name' => 'activity_id',
            'label' => 'Activity',
            'type' => 'multiselect',
            'validators' => [
                'required' => true
            ],
            'value' => [],
            'options' => []
        ]
    ];

    /**
     * Structure of dynamic form elements
     */
    protected static $profileFormFields = [
        [
            'name' => 'mobile',
            'label' => 'Mobile',
            'type' => 'mobileNo',
            'value' => ''
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'value' => ''
        ],
        [
            'name' => 'activity_id',
            'label' => 'Activity',
            'type' => 'multiselect',
            'value' => []
        ]
    ];

    protected static $tableFields = [
        [
            'name' => 'id',
            'type' => 'text',
            'tableField' => 'id'
        ],
        [
            'name' => 'mobile',
            'type' => 'mobileNo',
            'tableField' => 'mobile'
        ],
        [
            'name' => 'email',
            'type' => 'email',
            'tableField' => 'email'
        ],
        [
            'name' => 'language',
            'type' => 'text',
            'tableField' => 'language_id'
        ],
        [
            'name' => 'userActivity',
            'type' => 'badge',
            'tableField' => ''
        ],
        [
            'name' => 'status',
            'type' => 'badge',
            'tableField' => 'status'
        ]
    ];

    /**
     * Structure of dynamic filter form elements
     */
    protected static $filterFields = [
        [
            'name' => 'mobile',
            'label' => 'Mobile',
            'type' => 'text',
            'validators' => [
                'required' => false
            ],
            'value' => ''
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'validators' => [
                'required' => false
            ],
            'options' => [
                ['value' => 'PENDING', 'label' => 'PENDING'],
                ['value' => 'APPROVED', 'label' => 'APPROVED'],
                ['value' => 'REJECTED', 'label' => 'REJECTED']
            ],
            'value' => ''
        ]
    ];

    public function kyc()
    {
        // Assuming a foreign key relationship with user_id
        return $this->belongsTo(UserKyc::class, 'id', 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        $kycInfo = $this->kyc;

        $fullName = isset($kycInfo->full_name) ? $kycInfo->full_name : $this->email;
        $profilePhoto = isset($kycInfo->profile_photo) ? $kycInfo->profile_photo : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbxrsLPhlg4XNe_zzbzTuha2u6PscKNIOuzf2jb7M&s';

        return [
            'user_id' => $this->id,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'full_name' => $fullName,
            'activities' => $this->getUserAccessList($this->id),
            'thumbnail' => $profilePhoto,
            // 'menu' => $menu
        ];
    }

    public function getUserAccessList()
    {
        return $this->getRoleNames();
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param  string|int|\Spatie\Permission\Contracts\Permission  $permission
     * @param  string|null  $guardName
     *
     * @throws PermissionDoesNotExist
     */
    public function checkHasPermissionTo($permission, $guardName = null): bool
    {
        Redis::flushDB();
        // Connect to Redis (assuming it's running on localhost with default port)
        $redis = new Redis();

        // Generate a unique cache key based on the user ID and permission name
        $cacheKey = 'permission:' . $this->id . ':' . $permission;

        // Check if the permission check result exists in Redis cache
        if ($redis::get($cacheKey)) {
            return (bool) $redis::get($cacheKey);
        }

        // If not in cache, then perform the permission check using Spatie's hasPermissionTo method
        $hasPermission = $this->hasPermissionTo($permission, $guardName);

        // Cache the result with a TTL of 60 seconds (1 minute)
        $redis::setex($cacheKey, 60, (int) $hasPermission);

        return $hasPermission;
    }

    /**
     * Getter function for tableElements
     *
     * @return array
     */
    public static function getTableFields(): array
    {
        $fields = [];
        $tableFieldsCollection = collect(self::$tableFields);
        $fields = $tableFieldsCollection->map(function (array $field) {
            return [
                'name' => $field['name'],
                'type' => $field['type'],
                'tableField' => $field['tableField']
            ];
        })->all();
        return $fields;
    }

    /**
     * Getter function for formElements
     *
     * @return array
     */
    public static function getFormFields(): array
    {
        $fields = [];
        $formFieldsCollection = collect(self::$formFields);
        $fields = $formFieldsCollection->map(function (array $field) {
            return [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $field['options'] ?? [],
                'value' => $field['value'] ?? '',
                'validators' => $field['validators'] ?? (object)[]
            ];
        })->all();
        return $fields;
    }

    /**
     * Getter function for formElements
     *
     * @return array
     */
    public static function getProfileFormFields(): array
    {
        $fields = [];
        $formFieldsCollection = collect(self::$profileFormFields);
        $fields = $formFieldsCollection->map(function (array $field) {
            return [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                // 'options' => $field['options'] ?? [],
                'value' => $field['value'] ?? '',
                // 'validators' => $field['validators'] ?? (object)[]
            ];
        })->all();
        return $fields;
    }

    /**
     * Getter function for filterElements
     *
     * @return array
     */
    public static function getFilterFields(): array
    {
        $fields = [];
        $filterFieldsCollection = collect(self::$filterFields);
        $fields = $filterFieldsCollection->map(function (array $field) {
            return [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $field['options'] ?? [],
                'validators' => $field['validators'] ?? (object)[]
            ];
        })->all();
        return $fields;
    }

    /**
     * Get the country associated with the State
     * @return object
     */
    public function language()
    {
        return $this->belongsTo(
            related: Language::class,
            foreignKey: 'language_id',
            ownerKey: 'id'
        );
    }

    /**
     * Get the user associated with the Acivity
     * @return object
     */
    public function userActivity()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }

    /**
     * Interact with the user's address.
     */
    // protected function mobile(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn (mixed $value) => '+91' . $value,
    //     );
    // }

    /**
     * The user that has many user doc
     */
    public function userDoc()
    {
        return $this->belongsToMany(DrkDoc::class, 'uapp_user_doc', 'user_id', 'doc_id')->withPivot(['status', 'rejection_reason_id']);
    }

    /**
     * Get the user associated with the userKyc
     * @return object
     */
    public function userKyc()
    {
        return $this->hasOne(
            related: UserKyc::class,
            foreignKey: 'user_id',
        );
    }

    // /**
    //  * Set the mobile number attribute with '+91' prefix if not already present.
    //  *
    //  * @param string $value
    //  */
    // public function setMobileNumberAttribute($value)
    // {
    //     // Check if the mobile number already contains '+91', and if not, add it
    //     if (strpos($value, '+91') !== 0) {
    //         $this->attributes['mobile_number'] = '+91' . $value;
    //     } else {
    //         $this->attributes['mobile_number'] = $value;
    //     }
    // }

    /**
     * Get the mobile number attribute with '+91' prefix if not already present.
     *
     * @param string $value
     * @return string
     */
    public function getMobileNumberAttribute($value)
    {
        if (strpos($value, '+91') !== 0) {
            return '+91' . $value;
        } else {
            return $value;
        }
    }
}
