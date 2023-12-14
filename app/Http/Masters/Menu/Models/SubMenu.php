<?php

namespace App\Http\Masters\Menu\Models;

use App\Http\Masters\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMenu extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_sub_menu';

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
        'menu_id',
        'name',
        'submenulink',
        'icon',
        'status'
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
            'name' => 'submenulink',
            'label' => 'Sub Menu Link',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 50,
                'minLength' => 2,
            ],
            'value' => ''
        ],
        [
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'validators' => [
                'required' => false,
                'maxLength' => 50,
                'minLength' => 2,
                'readonly' => true
            ],
            'value' => "<i class='fa-solid fa-circle'></i>"
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
            'name' => 'menu',
            'type' => 'text',
            'tableField' => 'menu_id'
        ],
        [
            'name' => 'name',
            'type' => 'text',
            'tableField' => 'name'
        ],
        [
            'name' => 'subMenuLink',
            'type' => 'text',
            'tableField' => 'submenulink'
        ],
        [
            'name' => 'icon',
            'type' => 'text',
            'tableField' => 'icon'
        ],
        [
            'name' => 'status',
            'type' => 'badge',
            'tableField' => 'status'
        ]
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
    public static function getFilterFields(array $extraFields): array
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
        if($extraFields){
            $extraFields['validators']['required'] = false;
            $extraFields['value'] = '';
            $fields = array_merge([$extraFields],$fields);
        }
        return $fields;
    }

    /**
     * Get the country associated with the State
     * @return object
     */
    public function menu()
    {
        return $this->belongsTo(
            related: Menu::class,
            foreignKey: 'menu_id',
            ownerKey: 'id'
        );
    }

     /**
     * The users that belong to the Many activity(Role).
     */
    public function subMenu()
    {
        return $this->hasMany(SubMenu::class, 'sub_menu_id')->where('status','APPROVED');
        // return $this->belongsToMany(SubMenu::class, 'gen_menu_activity','activity_id', 'sub_menu_id');
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

}
