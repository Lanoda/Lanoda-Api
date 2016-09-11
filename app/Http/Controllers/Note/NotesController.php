<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\ApiError;
use App\Http\Controllers\Helpers\HttpStatusCode;
use App\Http\Controllers\Helpers\ModelHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

use App\User;
use App\Contact;
use App\Note;


class NotesController extends Controller
{

    /**
     * Register any middleware.
     *
     * @return Response
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showlist(Request $request)
    {
        $notes = Note::where('user_id', Auth::user()->id);
        $transformCollection = 'App\Http\Controllers\Note\NotesController::transformCollection';
        $modelHelper = new ModelHelper();
        $content = $modelHelper->GetListResult($notes, $request->all(), 'Notes', 'id', $transformCollection);
        $apiResult = new ApiResult($content, true);
        return $apiResult->GetJsonResponse('Ok');
    }

    /**
     * Display a listing of the resource for a contact.
     *
     * @return Response
     */
    public function showlistForContact(Request $request, Contact $contact)
    {
        if (Auth::user()->id != $contact->user_id)
        {
            $apiResult = new ApiErrorResult('NoteGet_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        $notes = Note::where('contact_id', $contact->id);
        $transformCollection = 'App\Http\Controllers\Note\NotesController::transformCollection';
        $modelHelper = new ModelHelper();
        $content = $modelHelper->GetListResult($notes, $request->all(), 'Notes', 'id', $transformCollection);
        $apiResult = new ApiResult($content, true);
        return $apiResult->GetJsonResponse('Ok');
    }

    /**
     * Create a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try
        {
            $noteModel = $request->all();
            $noteModel["user_id"] = Auth::user()->id;
            $note = Note::create($noteModel);
            $apiResult = new ApiResult($this->transform($note), true);
            return $apiResult->GetJsonResponse('Ok');
        }
        catch (Exception $e)
        {
            $apiResult = new ApiErrorResult('NoteCreate_InternalServerError');
            return $apiResult->GetJsonResponse('InternalServerError');
        }
    }

    /**
     * Display a specific resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Note $note)
    {
        if (Auth::user()->id != $note->user_id)
        {
            $apiResult = new ApiErrorResult('NoteGet_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        $apiResult = new ApiResult($this->transform($note), true);
        return $apiResult->GetJsonResponse('Ok');
    }

    /**
     * Update a resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Note $note)
    {
        $rules = array(
            'user_id'    => 'required',
            'email'      => 'required|email',
        );

        if (Auth::user()->id != $note->user_id)
        {
            $apiResult = new ApiErrorResult('NoteUpdate_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        try 
        {
            $note->update($request->all(), $rules);
            $apiResult = new ApiResult($this->transform($note), true);
            return $apiResult->GetJsonResponse('Ok');
        }
        catch(Exception $e) 
        {
            $apiResult = new ApiErrorResult('NoteUpdate_InternalServerError');
            return $apiResult->GetJsonResponse('InternalServerError');
        }

    }

    /**
     * Delete a resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Note $note)
    {
        if (Auth::user()->id != $note->user_id)
        {
            $apiResult = new ApiErrorResult('NoteDelete_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        try
        {
            $note->delete();

            $apiResult = new ApiResult(null, true);
            return $apiResult->GetJsonResponse('NoContent');
        }
        catch (Exception $e)
        {
            $apiResult = new ApiErrorResult('NoteDelete_InternalServerError');
            return $apiResult->GetJsonResponse('InternalServerError');
        }
    }

    /**
     * Apply 'Transform' to a collection of contacts.
     *
     * @param  $contacts
     * @return array
     */
    public static function transformCollection($notes)
    {
        if ($notes == null) return array();
        return array_map('App\Http\Controllers\Note\NotesController::transform', $notes->toArray());
    }

    /**
     * Transform a Contact
     *
     * @param  $contact
     * @return array
     */
    public static function transform($note) 
    {
        return [
            'id'            => $note['id'],
            'user_id'       => $note['user_id'],
            'contact_id'    => $note['contact_id'],
            'type_id'       => $note['type_id'],
            'title'         => $note['title'],
            'body'          => $note['body'],
            'created_at'    => $note['created_at'],
            'updated_at'    => $note['updated_at'],
        ];
    }
}
