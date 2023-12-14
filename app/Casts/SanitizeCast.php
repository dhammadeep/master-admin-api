<?php

namespace App\Casts;

use App\Utilities\StringFieldUtility;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class SanitizeCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return StringFieldUtility::sanitizeData($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return StringFieldUtility::sanitizeData($value);
    }
}
