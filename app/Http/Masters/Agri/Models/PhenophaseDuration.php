<?php

namespace App\Http\Masters\Agri\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Masters\Agri\Models\Variety;
use App\Http\Masters\Agri\Models\Commodity;
use App\Http\Masters\Agri\Models\Phenophase;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhenophaseDuration extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_phenophase_duration';

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
        'variety_id',
        'phenophase_id',
        'start_das',
        'end_das'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Structure of dynamic form elements
     */
    protected static $formFields = [
        [
            'name' => 'variety_id',
            'label' => 'Variety',
            'type' => 'select',
            'validators' => [
                'required' => true
            ],
            'value' => ''
        ],
        [
            'name' => 'start_das',
            'label' => 'Start Das',
            'type' => 'numeric',
            'validators' => [
                'required' => true,
                'maxLength' => 9,
                'minLength' => 1,
            ],
            'value' => ''
        ],
        [
            'name' => 'end_das',
            'label' => 'End Das',
            'type' => 'numeric',
            'validators' => [
                'required' => true,
                'maxLength' => 9,
                'minLength' => 1,
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
            'name' => 'variety',
            'type' => 'text',
            'tableField' => 'variety_id'
        ],
        [
            'name' => 'phenophase',
            'type' => 'text',
            'tableField' => 'phenophase_id'
        ],
        [
            'name' => 'startDas',
            'type' => 'text',
            'tableField' => 'start_das'
        ],
        [
            'name' => 'endDas',
            'type' => 'text',
            'tableField' => 'end_das'
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
            // $extraFields['validators']['required'] = false;
            // $extraFields['value'] = '';
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
    public function variety()
    {
        return $this->belongsTo(
            related: Variety::class,
            foreignKey: 'variety_id',
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
