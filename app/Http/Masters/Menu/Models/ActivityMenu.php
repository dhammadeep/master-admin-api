<?php

namespace App\Http\Masters\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Http\Modules\Authentication\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityMenu extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_menu_activity';

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
        'activity_id',
        'sub_menu_id'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Structure of dynamic form elements
     */
    protected static $formFields = [
    ];

     /**
     * Structure of dynamic table elements
     */
    protected static $tableFields = [
        [
            'name' => 'id',
            'type' => 'text',
            'tableField' => 'activity_id'
        ],
        [
            'name' => 'activity',
            'type' => 'text',
            'tableField' => 'name'
        ],
        [
            'name' => 'submenu',
            'type' => 'badge',
            'tableField' => ''
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
     * Get the subMenu associated with the Activity
     * @return object
     */
    public function subMenu()
    {
        return $this->belongsTo(
            related: SubMenu::class,
            foreignKey: 'sub_menu_id',
            ownerKey: 'id'
        );
        //  return $this->hasMany(SubMenu::class, 'sub_menu_id')->where('status','APPROVED');
        // return $this->belongsToMany(SubMenu::class, 'gen_menu_activity','activity_id', 'sub_menu_id');
    }

    // /**
    //  * Get the menu associated with the SubMenu
    //  * @return object
    //  */
    // public function activity()
    // {
    //     return $this->hasMany(SubMenu::class, 'menu_id')->where('status','APPROVED');
    // }

    /**
     * Get the activity associated with the user
     * @return object
     */
    public function activity()
    {
        return $this->belongsTo(
            related: Activity::class,
            foreignKey: 'activity_id',
            ownerKey: 'id'
        );
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
