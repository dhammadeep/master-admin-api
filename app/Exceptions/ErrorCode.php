<?php

namespace App\Exceptions;

class ErrorCode
{
    // System Error
    public const SERVER_ERROR = "CDT_SYS_ERR_500";
    public const VALUE_ERROR = "CDT_SYS_ERR_502";
    public const KEY_ERROR = "CDT_SYS_ERR_503";
    public const SERVER_REQUEST_TIMEOUT = "CDT_SYS_ERR_504";
    public const ACCESS_FORBIDDEN_ERROR = "CDT_SYS_ERR_505";
    public const SERVICE_UNAVAILABLE = "CDT_SYS_ERR_506";
    public const ATTRIBUTE_ERROR = "CDT_SYS_ERR_507";
    // Framework Error UNAUTHORIZED_USER
    public const UNAUTHORIZED_USER = "CDT_FW_ERR_491";
    public const DOES_NOT_EXIST = "CDT_FW_ERR_481";
    public const REQUIRED_FIELD = "CDT_FW_ERR_482";
    public const REQUEST_FAILED = "CDT_FW_ERR_483";
    public const JSON_PARSE_ERROR = "CDT_FW_ERR_484";
    public const SERIALIZER_ERROR = "CDT_FW_ERR_485";
    public const FIELD_ERROR = "CDT_FW_ERR_486";
    public const VALIDATION_ERROR = "CDT_FW_ERR_487";
    public const QUERY_EXCEPTION = "CDT_FW_ERR_488";
    public const MODEL_NOT_FOUND = "CDT_FW_ERR_489";
    public const PAGE_NOT_FOUND = "CDT_FW_ERR_490";
    // Custom Error
    public const DATA_NOT_CREATED = "CDT_CUST_ERR_601";
    public const UPDATE_FAILED = "CDT_CUST_ERR_602";
    public const DELETE_FAILED = "CDT_CUST_ERR_603";
    public const COLUMN_NOT_FOUND = "CDT_CUST_ERR_607";
    public const DATA_NOT_FOUND = "CDT_CUST_ERR_608";

    public static function is_set($code)
    {
        if (defined("self::$code")) {
            return constant("self::$code");
        }

        return false;
    }
}
