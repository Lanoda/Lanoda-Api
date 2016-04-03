<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Contact;
use App\Http\Requests\Request;


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
    public function index()
    {
        $contacts = Contact::all();
        return Response::json([
            'data' => $this->transformCollection($contacts),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Create a new resource.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display a specific resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) 
        {
            return Response::json([
                'error' => [
                    'message' => 'Contact does not exist',
                ]
            ], 404);
        }

        return Response::json([
            'data' => $this->transform($contact->toArray()),
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
    public function update($id, Request $request)
    {
        Contact::find($id)->update($request->all());
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
