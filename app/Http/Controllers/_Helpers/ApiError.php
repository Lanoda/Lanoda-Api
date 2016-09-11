<?php

namespace App\Http\Controllers\Helpers;

class ApiError
{
    public $Id;
    public $Message;
    public $Time;

    public function __construct($id)
    {
        $this->Id = $id;
        $this->Message = $this->GetErrorMessage($id);
        $this->Time = date('Y-m-d H:i:s');
    }

    private function GetErrorMessage($errorId) {
        $error = Array(
            'AuthorizeApp_ClientNotFound' => 'ApiClient was not found.',
            'AuthorizeApp_MissingRequiredParameters' => 'Missing required parameters.',
            'AuthorizeApp_InvalidCredentials' => 'Invalid Credentials.',
        );

        return $error[$errorId];
    }
}
