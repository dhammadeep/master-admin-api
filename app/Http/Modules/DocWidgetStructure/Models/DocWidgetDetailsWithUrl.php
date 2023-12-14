<?php

namespace App\Http\Modules\DocWidgetStructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocWidgetDetailsWithUrl extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drk_doc_widget_details_with_url';

}
