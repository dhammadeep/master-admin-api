<?php

namespace App\Http\Modules\Authentication\Models;

use App\Http\Modules\Gen\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Menu\Models\SubMenu;
use App\Http\Modules\Gen\Models\MenuActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Modules\Authentication\Models\UserActivity;

class Activity extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth_activity';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Structure of dynamic form elements
     */
    protected static $formFields = [
        [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 50,
                'minLength' => 2,
            ],
            'value' => ''
        ],
        [
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
            'validators' => [
                'required' => false,
                'maxLength' => 255,
                'minLength' => 4,
            ],
            'value' => ''
        ],
        [
            'name' => 'permission_id',
            'label' => 'Permission',
            'type' => 'multiselect',
            'validators' => [
                'required' => true
            ],
            'value' => []
        ]
    ];

     /**
     * Structure of dynamic table elements
     */
    protected static $tableFields = [
        [
            'name' => 'id',
            'type' => 'text',
            'tableField' => 'id'
        ],
        [
            'name' => 'name',
            'type' => 'text',
            'tableField' => 'name'
        ],
        [
            'name' => 'guardName',
            'type' => 'text',
            'tableField' => 'guard_name'
        ],
        [
            'name' => 'permissions',
            'type' => 'badge',
            'tableField' => ''
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'tableField' => ''
        ]
        // [
        //     'name' => 'status',
        //     'type' => 'badge',
        //     'tableField' => 'status'
        // ]
    ];

    /**
     * Structure of dynamic filter elements
     */
    protected static $filterFields = [
        [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'validators' => [
                'required' => false
            ],
            'value' => ''
        ],
        [
            'name' => 'permission_id',
            'label' => 'Permission',
            'type' => 'select',
            'options' => [],
            'validators' => [
                'required' => false
            ],
            'value' => []
        ],
        // [
        //     'name' => 'status',
        //     'label' => 'Status',
        //     'type' => 'select',
        //     'validators' => [
        //         'required' => false
        //     ],
        //     'options' => [
        //         ['value' => 'PENDING', 'label' => 'PENDING'],
        //         ['value' => 'APPROVED', 'label' => 'APPROVED'],
        //         ['value' => 'REJECTED', 'label' => 'REJECTED']
        //     ],
        //     'value' => ''
        // ]
    ];


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
                'tableField' => $field['tableField'],
                'type' => $field['type']
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
                'validators' => $field['validators'] ?? (object)[],
                'value' => $field['value'] ?? ''
            ];
        })->all();
        return $fields;
    }


    /**
     * Set the Name attribute in upper case.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function Name(): Attribute
    {
        return Attribute::make(
            set: fn($value, $attributes) => $attributes['Name'] = ucwords(strtolower($value)),
        );
    }

    /**
     * The users that belong to the Many activity(Role).
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->using(UserActivity::class);
    }

    /**
     * The menus that belong to the Many activity(Role).
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class)->using(MenuActivity::class);
    }

    /**
     * The users that belong to the Many activity(Role).
     */
    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'auth_activity_permission','activity_id', 'permission_id');
    }

     public function subMenu()
    {
        // return $this->hasMany(SubMenu::class, 'sub_menu_id')->where('status','APPROVED');
        return $this->belongsToMany(SubMenu::class, 'gen_menu_activity','activity_id', 'sub_menu_id');
    }

}
