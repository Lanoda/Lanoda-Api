<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Helpers\HttpStatusCode;
use Response;

class ApiResult
{
    public $Content;
    public $IsSuccess;
    public $Errors;

    public function __construct($content, $isSuccess, $errors = array()) 
    {
        $this->Content = $content;
        $this->IsSuccess = $isSuccess;

        if ($errors == null) 
        {
            $errors = array();
        }
        $this->Errors = $errors;
    }

    public function GetJsonResponse($statusCode)
    {
        return Response::json($this, $this->GetHttpStatusCode($statusCode));
    }

    private function GetHttpStatusCode($code)
    {
        $statusCodes = Array(
            'Ok' => HttpStatusCode::Ok,
            'BadRequest' => HttpStatusCode::BadRequest,
            'NotFound' => HttpStatusCode::NotFound,
            'Unauthorized' => HttpStatusCode::Unauthorized,
        );

        return $statusCodes[$code];
    }
}

class ApiErrorResult extends ApiResult 
{
    public function __construct($errorId) {
        $this->Content = null;
        $this->IsSuccess = false;
        $this->Errors = Array(new ApiError($errorId));
    }
}

