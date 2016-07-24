<?php

namespace App\Http\Controllers\Contact;

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


class ContactsController extends Controller
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
        $authUser = Auth::user();
        $contacts = Contact::where('user_id', $authUser->id);

        $transformCollection = 'App\Http\Controllers\Contact\ContactsController::transformCollection';
        $content = ModelHelper::GetListResult($contacts, $request->all(), 'Contacts', 'id', $transformCollection);
        
        $apiResult = new ApiResult($content, true);
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
        if ($request->input('firstname') == null && $request->input('lastname') == null 
            && $request->input('email') == null && $request->input('phone') == null)
        {
            $errorMsg = 'A Contact must have at least one of the following: \'firstname\', \'lastname\', \'email\', or \'phone\'.';
            $apiResult = ApiRequest::Error('ContactCreate_PrimaryFieldRequired', $errorMsg);
            return Response::json($apiResult, HttpStatuscode::BadRequest);
        }

        $authUser = Auth::user();
        $contactModel = $request->all();
        $contactModel["user_id"] = $authUser->id;

        $contact = Contact::create($request->all());
        $apiResult = new ApiResult($contact, true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Display a specific resource.
     *
     * @param  Contact  $contact
     * @return Response
     */
    public function show(Contact $contact)
    {
        $authUser = Auth::user();
        if ($authUser->id != $contact->user_id)
        {
            $apiResult = ApiResult::Error('ContactGet_Unauthorized', 'You are not authorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        $apiResult = new ApiResult($this->transform($contact), true);
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
    public function update(Request $request, Contact $contact)
    {
        $rules = array(
            'user_id'    => 'required',
            'email'      => 'required|email',
        );

        $authUser = Auth::user();
        if ($authUser->id = $contact->user_id)
        {
            $apiResult = ApiResult::Error('ContactUpdate_Unauthorized', 'You are not authorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        try 
        {
            $success = $contact->update($request->all(), $rules);
            $contact->save();

            $apiResult = new ApiResult($this->transform($contact), true);
            return Response::json($apiResult, HttpStatusCode::Ok);
        }
        catch(Exception $e)
        {
            $apiResult = ApiResult::Error('ContactUpdate_UpdateFailed', 'Update failed, the server encountered an error.');
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
        }

    }

    /**
     * Delete a resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Contact $contact)
    {
        $authUser = Auth::user();
        if ($authUser->id != $contact->user_id)
        {
            $apiResult = ApiResult::Error('ContactDelete_Unauthorized', 'Delete failed, You are not authorized to access this resource.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        try 
        {
            $contact->delete();

            $apiResult = new ApiResult(null, true);
            return Response::json($apiResult, HttpStatusCode::NoContent);
        }
        catch (Exception $e)
        {
            $apiResult = ApiResult::Error('ContactDelete_DeleteFailed', 'Delete failed, the server encountered an error.');
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
        }
    }


    /**
     * Apply 'Transform' to a collection of contacts.
     *
     * @param  $contacts
     * @return array
     */
    public static function transformCollection($contacts)
    {
        return array_map('App\Http\Controllers\Contact\ContactsController::transform', $contacts->toArray());
    }

    /**
     * Transform a Contact
     *
     * @param  $contact
     * @return array
     */
    public static function transform($contact) 
    {
        return [
            'id'        => $contact['id'],
            'firstname' => $contact['firstname'],
            'middlename'=> $contact['middlename'],
            'lastname'  => $contact['lastname'],
            'phone'     => $contact['phone'],
            'email'     => $contact['email'],
            'address'   => $contact['address'],
            'age'       => $contact['age'],
            'birthday'  => $contact['birthday'],
        ];
    }
}
