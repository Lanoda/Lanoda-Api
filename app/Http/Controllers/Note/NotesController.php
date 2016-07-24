<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\ApiError;
use App\Http\Controllers\Helpers\HttpStatusCode;

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
    public function showlist()
    {
        $authUser = Auth::user();
        $apiResult = new ApiResult($this->transformCollection($authUser->notes), true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Display a listing of the resource for a contact.
     *
     * @return Response
     */
    public function showlistForContact(Contact $contact)
    {
        $authUser = Auth::user();
        if ($authUser->id != $contact->user_id)
        {
            $apiResult = ApiResult::Error('NoteGet_Unauthorized', 'You are not authorized to access this resource.');
            return Response::Json($apiResult, HttpStatusCode::Unauthorized);
        }

        $apiResult = new ApiResult($this->transformCollection($contact->notes), true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Create a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();

        try
        {
            $noteModel = $request->all();
            $noteModel["user_id"] = $authUser->id;
            $note = Note::create($noteModel);
            $apiResult = new ApiResult($this->transform($note), true);
            return Response::json($apiResult, HttpStatusCode::Ok);
        }
        catch (Exception $e)
        {
            $apiResult = ApiResult::Error('NoteCreate_InternalServerError', 'Create failed, the server encountered an error.');
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
        }
    }

    /**
     * Display a specific resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Note $note)
    {
        $authUser = Auth::user();
        if ($authUser->id != $note->user_id)
        {
            $apiResult = ApiResult::Error('NoteGet_Unauthorized', 'You are unauthorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        $apiResult = new ApiResult($this->transform($note), true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Display a form for editing the resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

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
        $authUser = Auth::user();
        if ($authUser->id != $note->user_id)
        {
            $apiResult = ApiResult::Error('NoteUpdate_Unauthorized', 'Update failed, you are not authorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        try 
        {
            $note->update($request->all(), $rules);
            $note->save();

            $apiResult = new ApiResult($this->transform($note), true);
            return Response::json($apiResult, HttpStatusCode::Ok);
        }
        catch(Exception $e) 
        {
            $apiResult = ApiResult::Error('NoteUpdate_InternalServerError', 'Update Note failed, the server encountered an error.');
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
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
        $authUser = Auth::user();
        if ($authUser->id != $note->user_id) 
        {
            $apiResult = ApiResult::Error('NoteDelete_Unauthorized', 'Delete failed, you are not authorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        try
        {
            $note->delete();

            $apiResult = new ApiResult(null, true);
            return Response::json($apiResult, HttpStatusCode::NoContent);
        }
        catch (Exception $e)
        {
            $apiResult = ApiResult::Error('NoteDelete_InternalServerError', 'Delete failed, the server encountered an error.');
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
        }
    }

    /**
     * Apply 'Transform' to a collection of contacts.
     *
     * @param  $contacts
     * @return array
     */
    private function transformCollection($notes)
    {
        if ($notes == null) return array();
        return array_map([$this, 'transform'], $notes->toArray());
    }

    /**
     * Transform a Contact
     *
     * @param  $contact
     * @return array
     */
    private function transform($note) 
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
