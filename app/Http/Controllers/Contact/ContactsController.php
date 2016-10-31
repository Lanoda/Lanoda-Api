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
        $contacts = Contact::where('user_id', Auth::user()->id);

        $transformCollection = 'App\Http\Controllers\Contact\ContactsController::transformCollection';
        $modelHelper = new ModelHelper();
        $content = $modelHelper->GetListResult($contacts, $request->all(), 'Contacts', 'id', $transformCollection);
        
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
        if ($request->input('firstname') == null
            && $request->input('lastname') == null
            && $request->input('email') == null
            && $request->input('phone') == null)
        {
            $apiResult = new ApiErrorResult('ContactCreate_PrimaryFieldRequired');
            return $apiResult->GetJsonResponse('BadRequest');
        }

        $contactModel = $request->all();
        $contactModel["user_id"] = Auth::user()->id;

        $contact = Contact::create($request->all());
        $apiResult = new ApiResult($contact, true);
        return $apiResult->GetJsonResponse('Ok');
    }

    /**
     * Display a specific resource.
     *
     * @param  Contact  $contact
     * @return Response
     */
    public function show(Contact $contact)
    {
        if (Auth::user()->id != $contact->user_id)
        {
            $apiResult = new ApiErrorResult('ContactGet_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        $apiResult = new ApiResult($this->transform($contact), true);
        return $apiResult->GetJsonResponse('Ok');
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

        if (Auth::user()->id != $contact->user_id)
        {
            $apiResult = new ApiErrorResult('ContactUpdate_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        try 
        {
            $success = $contact->update($request->all(), $rules);
            $contact->save();

            $apiResult = new ApiResult($this->transform($contact), true);
            return $apiResult->GetJsonResponse('Ok');
        }
        catch(Exception $e)
        {
            $apiResult = new ApiErrorResult('ContactUpdate_UpdateFailed');
            return $apiResult->GetJsonResponse('InternalServerError');
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
        if (Auth::user()->id != $contact->user_id)
        {
            $apiResult = new ApiErrorResult('ContactDelete_Unauthorized');
            return $apiResult->GetJsonResponse('Unauthorized');
        }

        try 
        {
            $contact->delete();

            $apiResult = new ApiResult(null, true);
            return $apiResult->GetJsonResponse('NoContent');
        }
        catch (Exception $e)
        {
            $apiResult = new ApiErrorResult('ContactDelete_DeleteFailed');
            return $apiResult->GetJsonResponse('InternalServerError');
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
            'image_id'  => $contact['image_id'],
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
