<?php

namespace App\Http\Modules\IncompleteCase\Models;

use App\Casts\SanitizeCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseListIncompleteUnpaid extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_case_list_incomplete_unpaid';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $fields  = [
        'caseId' => 'case_id',
        'tenderType' => 'tender_type',
        'userFullName' => 'user_full_name',
        'userMobile' => 'user_mobile',
        'languageName' => 'language_name'
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
        ],
        [
            'name' => 'tenderType',
            'type' => 'text',
        ],
        [
            'name' => 'userFullName',
            'type' => 'text',
        ],
        [
            'name' => 'userMobile',
            'type' => 'mobileNo',
        ],
        [
            'name' => 'languageName',
            'type' => 'text',
        ]
    ];

    protected static $filterFields = [
        //     [
        //         'name' => 'case_id',
        //         'label' => 'Case Id',
        //         'type' => 'text',
        //     ],
        //     [
        //         'name' => 'crop_type',
        //         'label' => 'Crop Type',
        //         'type' => 'text',
        //     ],
        //     [
        //         'name' => 'user_full_name',
        //         'label' => 'User Name',
        //         'type' => 'text',
        //         'tableComponent' => 'RegularText',
        //     ],
        //     [
        //         'name' => 'user_mobile',
        //         'label' => 'User Mobile',
        //         'type' => 'text',
        //         'tableComponent' => 'RegularText',
        //     ],
        //     [
        //         'name' => 'language_name',
        //         'label' => 'Language Name',
        //         'type' => 'text',
        //         'tableComponent' => 'RegularText',
        //     ],
        //     [
        //         'name' => 'payment_status',
        //         'label' => 'Payment Status',
        //         'type' => 'text',
        //         'tableComponent' => 'OptionBadge',
        //         'searchable' => 'Payment Status',
        //         'options' => [
        //             ['value' => 'PENDING', 'label' => 'Pending'],
        //             ['value' => 'PAID', 'label' => 'Paid'],
        //         ],
        //         'colors' => [
        //             'PENDING' => 'warning',
        //             'PAID' => 'success',
        //         ],
        //     ],
        //     [
        //         'name' => 'validation',
        //         'label' => 'Validation',
        //         'type' => 'text',
        //         'tableComponent' => 'OptionBadge',
        //         'options' => [
        //             ['value' => 'user_status', 'label' => 'User Status'],
        //             ['value' => 'company_status', 'label' => 'Company Status'],
        //             ['value' => 'warehouse_status', 'label' => 'Warehouse Status'],
        //             ['value' => 'bank_account_status', 'label' => 'Bank Account Status'],
        //         ],
        //         'colors' => [
        //             'user_status' => 'warning',
        //             'company_status' => 'success',
        //             'warehouse_status' => 'warning',
        //             'bank_account_status' => 'success',
        //         ],
        //     ],
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
