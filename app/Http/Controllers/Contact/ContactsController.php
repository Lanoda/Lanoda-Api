<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\ApiError;
use App\Http\Controllers\Helpers\HttpStatusCode;
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
    public function index(User $user)
    {
        $authUser = Auth::user();
        if ($authUser == null || $authUser->id != $user->id) {
            $apiErrors = array(new ApiError('ContactsGet_Unauthorized', 'You are not authorized to access this resource.'));
            $apiResult = new ApiResult(null, false, $apiErrors);
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }
        
        $apiResult = new ApiResult($this->transformCollection($user->contacts), true);
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
        if (!$request->has('user_id')) 
        {
            $apiErrors = array(new ApiError('ContactsPost_UserIdRequired', 'Contact must have a user_id.'));
            $apiResult = new ApiResult(null, false, $apiErrors);
            return Response::json($apiResult, HttpStatusCode::BadRequest);
        }

        if ($request->input('firstname') == null && $request->input('lastname') == null 
            && $request->input('email') == null && $request->input('phone') == null)
        {
            $errorId = 'ContactsPost_PrimaryFieldRequired';
            $errorMsg = 'A Contact must have at least one of the following: First Name, Last name, Email, or Phone.';
            $apiErrors = array(new ApiError($errorId, $errorMsg));
            $apiResult = new ApiResult(null, false, $apiErrors);
            return Response::json($apiResult, HttpStatuscode::BadRequest);
        }

        $contact = Contact::create($request->all());
        $apiResult = new ApiResult($contact, true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Display a specific resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(User $user, Contact $contact)
    {
        if (!$contact) 
        {
            $apiErrors = array(new ApiError('ContactGet_NotFound', 'Contact not found.'));
            $apiResult = new ApiResult(null, false, $apiErrors);
            return Response::json($apiResult, HttpStatusCode::NotFound);
        }

        $apiResult = new ApiResult($this->transform($contact->toArray()), true);
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
    public function update(User $user, Contact $contact, Request $request)
    {
        $rules = array(
            'user_id'    => 'required',
            'email'      => 'required|email',
        );

        try {
            $success = $contact->update($request->all(), $rules);
            $contact->save();

            $apiResult = new ApiResult($this->transform($contact), true);
            return Response::json($apiResult, HttpStatusCode::Ok);
        }
        catch(Exception $e) {
            $apiResult = new ApiResult(null, false, array(new ApiError('ContactPut_UpdateError', $e)));
            return Response::json($apiResult, HttpStatusCode::InternalServerError);
        }

    }

    /**
     * Delete a resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(User $user, Contact $contact)
    {
        $contact->delete();
    }


    /**
     * Apply 'Transform' to a collection of contacts.
     *
     * @param  $contacts
     * @return array
     */
    private function transformCollection($contacts)
    {
        return array_map([$this, 'transform'], $contacts->toArray());
    }

    /**
     * Transform a Contact
     *
     * @param  $contact
     * @return array
     */
    private function transform($contact) 
    {
        return [
            'firstname' => $contact['firstname'],
            'middlename'=> $contact['middlename'],
            'lastname'  => $contact['lastname'],
            'phone'     => $contact['phone'],
            'email'     => $contact['email'],
            'address'   => $contact['address'],
            'age'       => $contact['age'],
            'birthday'  => $contact['birthday'],
            'BelongsTo' => $contact['user']['firstname'],
        ];
    }
}
