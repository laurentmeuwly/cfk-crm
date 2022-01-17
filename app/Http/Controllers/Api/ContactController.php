<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Contracts\Store\StoreInterface;
use CloudCreativity\LaravelJsonApi\Http\Requests\CreateResource;
use CloudCreativity\LaravelJsonApi\Http\Requests\UpdateResource;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends JsonApiController
{
    public function update(StoreInterface $store, UpdateResource $request)
    {        
        //$updateRequest = new UpdateResource();
        $email='';
        if( isset(($request->all())['data']['attributes']['email']) ) {
            $email = ($request->all())['data']['attributes']['email'];
        }
        $contact = Contact::where('email', $email)->first();
        /*if (is_null($contact)) {
            parent::create($store, $request);
        } else {
            ($request->all())['data']['id'] = $contact->id;
            parent::update($store, $request);
        }*/
        parent::update($store, $request);
    }
}
