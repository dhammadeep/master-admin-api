<?php

namespace App\Http\Masters\Gen\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Geo\Models\Location;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Market extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_market';

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
        'location_id',
        'name',
        // 'is_benchmark'
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
            'name' => 'pincode',
            'label' => 'Pincode',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 6,
                'minLength' => 6
            ],
            'value' => ''
        ],
        [
            'name' => 'address',
            'label' => 'Address',
            'type' => 'text',
            'validators' => [
                'required' => true,
                'maxLength' => 150,
                'minLength' => 2
            ],
            'value' => ''
        ]
        // [
        //     'name' => 'is_benchmark',
        //     'label' => 'Benchmark',
        //     'type' => 'radio',
        //     'validators' => [
        //         'required' => true
        //     ],
        //     "options" => [
        //         [
        //             "label" => "Yes",
        //             "value" => 1
        //         ],
        //         [
        //             "label" => "No",
        //             "value" => 0
        //         ]
        //     ],
        //     'value' => ''
        // ]
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
            'name' => 'pincode',
            'type' => 'text',
            'tableField' => 'pincode'
        ],
        [
            'name' => 'address',
            'type' => 'text',
            'tableField' => 'location_id'
        ],
        // [
        //     'name' => 'is_benchmark',
        //     'type' => 'text',
        //     'tableField' => 'is_benchmark'
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
            'name' => 'pincode',
            'label' => 'Pincode',
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
     * Get the Location associated with the Market
     * @return object
     */
    public function location()
    {
        return $this->belongsTo(
            related: Location::class,
            foreignKey: 'location_id',
            ownerKey: 'id'
        );
    }

}
