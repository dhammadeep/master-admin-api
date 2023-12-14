<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Geo\Models\Location;
use App\Http\Masters\Warehouse\Models\WarehouseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_warehouse';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        'warehouse_type_id',
        'name',
        'code',
        'phone'
    ];


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
            'name' => 'code',
            'label' => 'Code',
            'type' => 'text',
            'validators' => [
                'required' => false,
            ],
            'value' => ''
        ],
        [
            'name' => 'phone',
            'label' => 'Phone',
            'type' => 'mobileNo',
            'validators' => [
                'required' => false,
                'maxLength' => 10,
                'minLength' => 10,
                'mobile' => true
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
            'type' => 'textarea',
            'validators' => [
                'required' => true,
                'maxLength' => 300,
                'minLength' => 6,
            ],
            'value' => ''
        ]
    ];


    protected static $tableFields = [
        [
            'name' => 'id',
            'type' => 'text',
            'tableField' => 'id'
        ],
        [
            'name' => 'pincode',
            'type' => 'text',
            'tableField' => 'pincode'
        ],
        [
            'name' => 'address',
            'type' => 'text',
            'tableField' => ''
        ],
        [
            'name' => 'warehouseType',
            'type' => 'text',
            'tableField' => 'warehouse_type_id'
        ],
        [
            'name' => 'name',
            'type' => 'text',
            'tableField' => 'name'
        ],
        [
            'name' => 'code',
            'type' => 'text',
            'tableField' => 'code'
        ],
        [
            'name' => 'phone',
            'type' => 'mobileNo',
            'tableField' => 'phone'
        ],
        [
            'name' => 'status',
            'type' => 'badge',
            'tableField' => 'status'
        ]
    ];

    /**
     * Structure of dynamic form elements
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
            'name' => 'code',
            'label' => 'Code',
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
            'name' => 'phone',
            'label' => 'Phone',
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

    /**
     * Get the warehouse associated with the State
     * @return object
     */
    public function warehouseType()
    {
        return $this->belongsTo(
            related: WarehouseType::class,
            foreignKey: 'warehouse_type_id',
            ownerKey: 'id'
        );
    }
}
