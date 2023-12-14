<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ResponseStatus
{
    // System Error
    public const SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const VALUE_ERROR = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const KEY_ERROR = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const SERVER_REQUEST_TIMEOUT = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const ACCESS_FORBIDDEN_ERROR = Response::HTTP_FORBIDDEN;
    public const SERVICE_UNAVAILABLE = Response::HTTP_SERVICE_UNAVAILABLE;
    public const ATTRIBUTE_ERROR = Response::HTTP_SERVICE_UNAVAILABLE;
    // Framework Error

    public const UNAUTHORIZED_USER = Response::HTTP_UNAUTHORIZED;
    public const DOES_NOT_EXIST = Response::HTTP_NOT_FOUND;
    public const REQUIRED_FIELD = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const REQUEST_FAILED = Response::HTTP_BAD_REQUEST;
    public const JSON_PARSE_ERROR = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const SERIALIZER_ERROR = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const FIELD_ERROR = Response::HTTP_BAD_REQUEST;
    public const VALIDATION_ERROR = Response::HTTP_BAD_REQUEST;
    public const QUERY_EXCEPTION = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const MODEL_NOT_FOUND = Response::HTTP_NOT_FOUND;
    public const PAGE_NOT_FOUND = Response::HTTP_NOT_FOUND;
    // Custom Error
    public const DATA_NOT_CREATED = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const UPDATE_FAILED = Response::HTTP_NOT_MODIFIED;
    public const DELETE_FAILED = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const COLUMN_NOT_FOUND = Response::HTTP_NOT_FOUND;
    public const DATA_NOT_FOUND = Response::HTTP_NOT_FOUND;
    public const SUCCESS = "Success Response";
}
