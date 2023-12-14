<?php

namespace App\Http\Masters\Agri\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Agri\Models\Commodity;
use App\Http\Masters\Agri\Models\Parameter;
use App\Http\Masters\Agri\Models\Phenophase;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommodityParameter extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_commodity_parameter';

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
        'parameter_type',
        'parameter_id'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Structure of dynamic form elements
     */
    protected static $formFields = [
        [
            'name' => 'parameter_type',
            'label' => 'Parameter Type',
            'type' => 'select',
            'validators' => [
                'required' => false
            ],
            'options' => [
                ['value' => 'Pre-Harvest', 'label' => 'Pre-Harvest'],
                ['value' => 'Post-Harvest', 'label' => 'Post-Harvest'],
                ['value' => 'Both', 'label' => 'Both']
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
            'name' => 'parameterType',
            'type' => 'text',
            'tableField' => 'parameter_type'
        ],
        [
            'name' => 'parameter',
            'type' => 'text',
            'tableField' => 'parameter_id'
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
            'name' => 'parameter_type',
            'label' => 'Parameter Type',
            'type' => 'select',
            'validators' => [
                'required' => false
            ],
            'options' => [
                ['value' => 'Pre-Harvest', 'label' => 'Pre-Harvest'],
                ['value' => 'Post-Harvest', 'label' => 'Post-Harvest'],
                ['value' => 'Both', 'label' => 'Both']
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
     * Get the Commodity associated with the Commodity Parameter.
     *
     * @return object
     */
    public function parameter()
    {
        return $this->belongsTo(
            related: Parameter::class,
            foreignKey: 'parameter_id',
            ownerKey: 'id'
        );
    }

    /**
     * Get the Commodity associated with the Commodity Parameter.
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
