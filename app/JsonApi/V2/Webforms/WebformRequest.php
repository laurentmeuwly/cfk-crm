<?php

namespace App\JsonApi\V2\Webforms;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class WebformRequest extends ResourceRequest
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
            'agreement' => 'required|boolean',
            'message' => 'required|string',
            'title' => JsonApiRule::toOne(),
            'source' => JsonApiRule::toOne()
        ];
    }

}
