<?php

namespace App\Http\Modules\CaseValidation\Models;

use App\Casts\SanitizeCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseListInitialVisitGeoPlot extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_case_list_initial_visit_for_geo_plot';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $fields  = [
        'caseId' => 'case_id',
        'cropType' => 'crop_type',
        'userFullName' => 'user_full_name',
        'userMobile' => 'user_mobile',
        'languageName' => 'language_name',
        'regionName' => 'region_name',
        'paymentStatus' => 'payment_status'
    ];

    protected $casts = [
        'user_mobile' => SanitizeCast::class,
        'language_name' => SanitizeCast::class,
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'case_id';

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
    protected $fillable = [];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

   /**
     * Structure of dynamic form elements
     */
    protected static $tableFields = [
        [
            'name' => 'id',
            'type' => 'text',
            'tableField' => 'case_id'
        ],
        [
            'name' => 'cropType',
            'type' => 'text',
            'tableField' => 'crop_type'
        ],
        [
            'name' => 'userFullName',
            'type' => 'text',
            'tableField' => 'user_full_name'
        ],
        [
            'name' => 'userMobile',
            'type' => 'mobileNo',
            'tableField' => 'user_mobile'
        ],
        [
            'name' => 'languageName',
            'type' => 'text',
            'tableField' => 'language_name'
        ],
        [
            'name' => 'regionName',
            'type' => 'text',
            'tableField' => 'region_name'
        ],
        [
            'name' => 'paymentStatus',
            'type' => 'badge',
            'tableField' => 'payment_status'
        ],
        [
            'name' => 'validation',
            'type' => 'badge',
            'tableField' => ''
        ],
    ];


    protected static $filterFields = [
             [
                 'name' => 'case_id',
                'label' => 'Case Id',
                 'type' => 'text',
                 'validators' => '',
                 'value' => ''
             ],
             [
                 'name' => 'crop_type_id',
                 'label' => 'Crop Type',
                'type' => 'select',
                'validators' => '',
                 'value' => '',
                 'searchable' => 'Crop Type',
                 'options' => [
                     ['value' => 1, 'label' => 'Few Months'],
                     ['value' => 2, 'label' => 'Few Weeks'],
                     ['value' => 3, 'label' => 'Harvested'],
                     ['value' => 4, 'label' => 'Warehoused'],
                 ],
             ],
             [
                 'name' => 'user_full_name',
                 'label' => 'User Name',
                 'type' => 'text',
                 'validators' => '',
                 'value' => ''
             ],
             [
                 'name' => 'user_mobile',
                 'label' => 'User Mobile',
                 'type' => 'text',
                 'validators' => '',
                 'value' => ''
             ],
             [
                 'name' => 'language_name',
                 'label' => 'Language Name',
                 'type' => 'select',
                 'validators' => '',
                 'value' => '',
                 'searchable' => 'Language Name',
                 'options' => [
                     ['value' => 'English', 'label' => 'English'],
                     ['value' => 'Hindi', 'label' => 'Hindi'],
                 ],
             ],
             [
                 'name' => 'region_name',
                 'label' => 'Region Name',
                 'type' => 'text',
                 'validators' => '',
                 'value' => ''
             ],
             [
                 'name' => 'payment_status',
                 'label' => 'Payment Status',
                 'type' => 'select',
                 'validators' => '',
                 'value' => '',
                 'searchable' => 'Payment Status',
                 'options' => [
                     ['value' => 'PENDING', 'label' => 'Pending'],
                     ['value' => 'PAID', 'label' => 'Paid'],
                     ['value' => 'FAILED', 'label' => 'Failed'],
                 ],
                 'colors' => [
                     'PENDING' => 'warning',
                     'PAID' => 'success',
                     'FAILED' => 'error',
                 ],
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
                'name'           => $field['name'],
                'type'           => $field['type'],
                'tableField'           => $field['tableField'],
                // 'tableComponent' => $field['tableComponent'],
                // 'colors'         => $field['colors'] ?? [],
                // 'searchable'     => $field['searchable'] ?? false,
            ];
        })->all();
        return $fields;
    }

    // public function __get($field)
    // {
    //     $dbFieldName = $this->fields[$field];
    //     return $this->attributes[$dbFieldName];
    // }

    // public function __set($field, $value)
    // {
    //     $dbFieldName = $this->fields[$field];
    //     return $this->attributes[$dbFieldName] = $value;
    // }

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
}
