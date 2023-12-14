<?php

namespace App\Http\Masters\Agri\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commodity extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_commodity';

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
        'logo',
        'forward_ready',
        'warehouse_ready',
        'payment_service_ready'
    ];

    protected $casts = [
        'forward_ready' => 'boolean',
        'warehouse_ready' => 'boolean',
        'payment_service_ready' => 'boolean',
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
            'name' => 'logo',
            'label' => 'Logo',
            'type' => 'file',
            'validators' => [
                'required' => true,
                "MIMEType" => [
                    "image/jpeg",
                    "image/jpg",
                    "image/png"
                ]
            ],
            'value' => ''
        ],
        [
            'name' => 'forward_ready',
            'label' => 'Forward Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => true
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => 1
                ],
                [
                    "label" => "No",
                    "value" => 0
                ]
            ]
        ],
        [
            'name' => 'warehouse_ready',
            'label' => 'Warehouse Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => true
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => 1
                ],
                [
                    "label" => "No",
                    "value" => 0
                ]
            ]
        ],
        [
            'name' => 'payment_service_ready',
            'label' => 'Payment Service Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => true
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => 1
                ],
                [
                    "label" => "No",
                    "value" => 0
                ]
            ]
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
            'name' => 'logo',
            'type' => 'imageLink',
            'tableField' => 'logo'
        ],
        [
            'name' => 'forwardReady',
            'type' => 'text',
            'tableField' => 'forward_ready'
        ],
        [
            'name' => 'warehouseReady',
            'type' => 'text',
            'tableField' => 'warehouse_ready'
        ],
        [
            'name' => 'paymentService',
            'type' => 'text',
            'tableField' => 'payment_service_ready'
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
            'name' => 'forward_ready',
            'label' => 'Forward Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => false
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => "1"
                ],
                [
                    "label" => "No",
                    "value" => "0"
                ]
            ]
        ],
        [
            'name' => 'warehouse_ready',
            'label' => 'Warehouse Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => false
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => "1"
                ],
                [
                    "label" => "No",
                    "value" => "0"
                ]
            ]
        ],
        [
            'name' => 'payment_service_ready',
            'label' => 'Payment Service Ready',
            'value' => '',
            'type' => 'radio',
            'validators' => [
                'required' => false
            ],
            "options" => [
                [
                    "label" => "Yes",
                    "value" => "1"
                ],
                [
                    "label" => "No",
                    "value" => "0"
                ]
            ]
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
}
