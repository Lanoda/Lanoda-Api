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

            'ApiTokenRequest_InvalidClientCredentials' => 'Invalid Client credentials.',
            'ApiTokenRequest_UserNotFound' => 'User not found.',

            'ApiTokenRefresh_ApiTokenNotFound' => 'Api Token not found.',
            'ApiTokenRefresh_InvalidCredentials' => 'Invalid credentials, \'client_id\'.',

            'ContactCreate_PrimaryFieldRequired' => 'A Contact must have at least one of the following: \'firstname\', \'lastname\', \'email\', or \'phone\'.',
            'ContactUpdate_Unauthorized' => 'You are not authorized to access this resource.',
            'ContactUpdate_UpdateFailed' => 'Update failed, the server encountered an error.',
            'ContactDelete_Unauthorized', 'Delete failed, You are not authorized to access this resource.',
            'ContactDelete_DeleteFailed', 'Delete failed, the server encountered an error.',

            'NoteGet_Unauthorized' => 'You are not authorized to access this resource.',
            'NoteCreate_InternalServerError' => 'Create failed, the server encountered an error.',
            'NoteGet_Unauthorized' => 'You are unauthorized to access this resource.',
            'NoteUpdate_Unauthorized' => 'Update failed, you are not authorized to access this resource.',
            'NoteUpdate_InternalServerError' => 'Update Note failed, the server encountered an error.',
            'NoteDelete_Unauthorized' => 'Delete failed, you are not authorized to access this resource.',
            'NoteDelete_InternalServerError', 'Delete failed, the server encountered an error.',
        );

        return $error[$errorId];
    }
}
