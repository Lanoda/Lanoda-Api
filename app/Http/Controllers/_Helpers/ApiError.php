<?php

namespace App\Http\Controllers\Helpers;

class ApiError
{
    public $Id;
    public $Message;
    public $Time;

    public function __construct($id, $message)
    {
        $this->Id = $id;
        $this->Message = $message;
        $this->Time = time();
    }
}