<?php

namespace App\Utilities;

class StringFieldUtility
{
    public static function sanitizeData($data)
    {
        $sanitizedData = preg_replace('/[^A-Za-z0-9\s]+/', '', $data);
        $sanitizedData = preg_replace('/\s+/', '', $sanitizedData);
        $sanitizedData = trim($sanitizedData);
        return $sanitizedData;
    }
}
