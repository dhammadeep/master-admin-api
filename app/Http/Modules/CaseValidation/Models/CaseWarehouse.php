<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseWarehouse extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_case_warehouse';

    public $timestamps = FALSE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'warehouse_lot_no',
        'total_bag_count',
        'no_of_stacks'
    ];

    /**
     * The user bank account that has many user bank account doc
     */
    public function warehouseDoc()
    {
        return $this->belongsToMany(DrkDoc::class, 'drk_warehouse_doc','case_warehouse_id', 'doc_id')->withPivot(['status', 'rejection_reason_id']);
    }



}
