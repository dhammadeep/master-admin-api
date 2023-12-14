<?php

namespace App\Http\Masters\Agri\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Agri\Models\Commodity;
use App\Http\Masters\Agri\Models\Phenophase;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlantpartColor extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_plantpart_color';

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
        'commodity_id',
        'phenophase_id',
        'name',
        'hexcode',
        'weightage'
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
                'required' => false,
                'maxLength' => 50,
                'minLength' => 2,
            ],
            'value' => ''
        ],
        [
            'name' => 'hexcode',
            'label' => 'Hexcode',
            'type' => 'text',
            'validators' => [
                'required' => false,
                'maxLength' => 50,
                'minLength' => 2,
            ],
            'value' => ''
        ],
        [
            'name' => 'weightage',
            'label' => 'Weightage',
            'type' => 'numeric',
            'validators' => [
                'required' => true,
                'maxLength' => 10,
                'minLength' => 1,
            ],
            'value' => null
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
            'name' => 'commodity',
            'type' => 'text',
            'tableField' => 'commodity_id'
        ],
        [
            'name' => 'phenophase',
            'type' => 'text',
            'tableField' => 'phenophase_id'
        ],
        [
            'name' => 'name',
            'type' => 'text',
            'tableField' => 'name'
        ],
        [
            'name' => 'hexcode',
            'type' => 'text',
            'tableField' => 'hexcode'
        ],
        [
            'name' => 'weightage',
            'type' => 'text',
            'tableField' => 'weightage'
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
            'name' => 'hexcode',
            'label' => 'Hexcode',
            'type' => 'text',
            'validators' => [
                'required' => false
            ],
            'value' => ''
        ],
        [
            'name' => 'weightage',
            'label' => 'Weightage',
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
            $fields = array_merge(array_values($extraFields),$fields);
        }
        return $fields;
    }

    /**
     * Get the Commodity associated with the Variety.
     *
     * @return object
     */
    public function commodity()
    {
        return $this->belongsTo(
            related: Commodity::class,
            foreignKey: 'commodity_id',
            ownerKey: 'id'
        );
    }

    /**
     * Get the Variety associated with the Variety.
     *
     * @return object
     */
    public function phenophase()
    {
        return $this->belongsTo(
            related: Phenophase::class,
            foreignKey: 'phenophase_id',
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
