<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

use App\User;
use App\Contact;


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
    public function index(User $user)
    {
        $apiResult = null;
        $authUser = Auth::user();
        if ($authUser == null || $authUser->id != $user->id) {
            return Response::Json([
                'data' => null,
                'error' => 'You are not authorized to access this resource.'
            ], 401);
        }

        $contacts = $user->contacts;
        $apiResult = $this->transformCollection($contacts);
        return Response::json($apiResult, 200);
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
        if (!$request->has('user_id')) {
            return Response::json([
                'data' => null,
                'error' => 'Contacts must have a user_id.'
            ], 200);
        }

        if ($request->input('firstname') == null
            && $request->input('lastname') == null
            && $request->input('email') == null
            && $request->input('phone') == null) {
            return Response::json([
                'data' => null,
                'error' => [
                    'error_id' => '', 
                    'message' => 'A Contact must have at least one of the following: First Name, Last Name, Email, or Phone.'
                ]
            ], 200);
        }

        $contact = Contact::create($request->all());
        return Response::json([
            'data' => $this->transform($contact),
        ], 200);
    }

    /**
     * Display a specific resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Contact $contact)
    {
        $contact = Contact::find($id);

        if (!$contact) 
        {
            return Response::json([
                'error' => ['message' => 'Contact does not exist',]
            ], 404);
        }

        return Response::json([
            'content' => $this->transform($contact->toArray()),
        ], 200);
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
    public function update(Request $request, $id)
    {
        $rules = array(
            'user_id'    => 'required',
            'email'      => 'required|email',
        );

        try {
            $contact = Contact::find($id);
            $success = $contact->update($request->all(), $rules);
            $contact->save();

            return Response::json([
                'content' => $contact, 
                'success' => $success,
                'errors' => [],
            ], 200);
        }
        catch(Exception $e) {
            return Response::json([
                'content' => $contact,
                'success' => false,
                'errors' => [$e]
            ], 500);
        }

    }

    /**
     * Delete a resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Contact::delete($id);
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
