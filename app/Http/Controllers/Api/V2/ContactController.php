<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\JsonApi\V2\Contacts\ContactSchema;
use App\JsonApi\V2\Contacts\ContactRequest;
use App\Http\Controllers\Actions as LocalActions;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Laravel\Http\Requests\AnonymousQuery;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use App\Models\Contact;

class ContactController extends Controller
{

    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
    use Actions\Update;
    use Actions\Destroy;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    use Actions\UpdateRelationship;
    use Actions\AttachRelationship;
    use Actions\DetachRelationship;

    public function saving(?Contact $contact, ContactRequest $request, AnonymousQuery $query): void
    {
        echo "saving";
    }

    public function email(ResourceRequest $request, Route $route)
    {
        echo "email";
        die();
    }

    public function add(ResourceRequest $request, Route $route)
    {
        $request = ResourceRequest::forResource(
            $resourceType = $route->resourceType()
        );
        $query = ResourceQuery::queryOne($resourceType);
        dd($query);


        // Get contact data from the request
        $contactData = $request->getContent();
var_dump($contactData);
        return response($contactData->email, 204);
        /*Contact::query();

        // Get contact data from the request
        $contactData = $request->all();

        // Check if the user already exists in the database
        $existingContact = Contact::where('email', $contactData['email'])->first();

        if ($existingContact) {
            // Contact exists, update the existing resource
            $this->update($existingContact, $request);
        } else {
            // Contact doesn't exist, create a new resource
            $this->create($request);
        }*/
    }

}
