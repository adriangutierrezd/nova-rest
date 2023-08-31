<?php 

namespace App\Controllers;

class BaseController {

    const JSON_RESPONSE_TYPE = 'JSON';
    const TEXT_RESPONSE_TYPE = 'TEXT';
    const AVAILABLE_RESPONSE_TYPES = [
        self::JSON_RESPONSE_TYPE,
        self::TEXT_RESPONSE_TYPE
    ];

    const CONTENT_TYPES = [
        self::JSON_RESPONSE_TYPE => 'application/json',
        self::TEXT_RESPONSE_TYPE => 'text/plain'
    ];

    const INVALID_RESPONSE_TYPE = 'Invalid response type';
}
