<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\JsonApi\V1\Contacts\ContactSchema;
use App\JsonApi\V1\Contacts\ContactRequest;
use App\Http\Controllers\Actions as LocalActions;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

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

}
