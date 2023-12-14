<?php

namespace App\Http\Modules\CaseValidation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseGeoPlottingDetails extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_case_geo_plot_details';

}
