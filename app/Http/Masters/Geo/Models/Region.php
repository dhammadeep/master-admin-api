<?php

namespace App\Http\Masters\Geo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geo_region';

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
        'name',
        'latitude_min',
        'latitude_max',
        'longitude_min',
        'longitude_max'
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
            'name' => 'latitude_min',
            'label' => 'Latitude Min',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 12,
                'minLength' => 4,
            ],
            'value' => ''
        ],
        [
            'name' => 'latitude_max',
            'label' => 'Latitude Max',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 12,
                'minLength' => 4,
            ],
            'value' => ''
        ],
        [
            'name' => 'longitude_min',
            'label' => 'Longitude Min',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 13,
                'minLength' => 4
            ],
            'value' => ''
        ],
        [
            'name' => 'longitude_max',
            'label' => 'Longitude Max',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 13,
                'minLength' => 4
            ],
            'value' => ''
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
            'name' => 'location',
            'type' => 'kmlMap',
            'tableField' => 'latitude_min'
        ],
        // [
        //     'name' => 'latitudeMin',
        //     'type' => 'text',
        //     'tableField' => 'latitude_min'
        // ],
        // [
        //     'name' => 'latitudeMax',
        //     'type' => 'text',
        //     'tableField' => 'latitude_max'
        // ],
        // [
        //     'name' => 'longitudeMin',
        //     'type' => 'text',
        //     'tableField' => 'longitude_min'
        // ],
        // [
        //     'name' => 'longitudeMax',
        //     'type' => 'text',
        //     'tableField' => 'longitude_max'
        // ],
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

}
