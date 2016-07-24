<?php

namespace App\Http\Controllers\Helpers;

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

    public static function Error($errorId, $errorMessage)
    {
        return new ApiResult(null, false, array(new ApiError($errorId, $errorMessage)));
    }
}