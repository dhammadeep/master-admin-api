<?php

namespace App\Exceptions;

class ResponseMessage
{
    // System Error
    public const SERVER_ERROR = "Something Went Wrong";
    public const VALUE_ERROR = "Invalid Request";
    public const KEY_ERROR = "key Not Found";
    public const SERVER_REQUEST_TIMEOUT = "Request Time Out";
    public const ACCESS_FORBIDDEN_ERROR = "Access Is Forbidden";
    public const SERVICE_UNAVAILABLE = "Server Not Ready To Handle";
    public const ATTRIBUTE_ERROR = "Attribute Not Found";
    // Framework Error
    public const UNAUTHORIZED_USER = "User Unauthorized";
    public const DOES_NOT_EXIST = "Data Not Found";
    public const REQUIRED_FIELD = "Required Field";
    public const REQUEST_FAILED = "Json Request Failed";
    public const JSON_PARSE_ERROR = "Json Parse Error";
    public const SERIALIZER_ERROR = "Serializer Error";
    public const FIELD_ERROR = "Field Error";
    public const VALIDATION_ERROR = "Validation Error";
    public const QUERY_EXCEPTION = "Database Or Query Exception";
    public const MODEL_NOT_FOUND = "Data Not Found";
    public const PAGE_NOT_FOUND = "Page Not Found";
    // Custom Error
    public const DATA_NOT_CREATED = "Data Not Created";
    public const SUCCESS = "Success response";
    public const UPDATE_FAILED = "Update Failed";
    public const DELETE_FAILED = "	Delete Failed";
    public const COLUMN_NOT_FOUND = "Column Not Found";
    public const PRODUCT_NOT_FOUND = "product Not Found";
    public const DATA_NOT_FOUND = "Data Not Found";
}
