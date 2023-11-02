<?php

namespace App\JsonApi\V2\Contacts;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class ContactRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'prefered_language' => 'required|string|min:2',
            'email' => 'required|string',
            'newsletter' => 'required|boolean',
            'agreed' => 'required|boolean',
            'title' => JsonApiRule::toOne(),
            'source' => JsonApiRule::toOne()
        ];
    }

}
