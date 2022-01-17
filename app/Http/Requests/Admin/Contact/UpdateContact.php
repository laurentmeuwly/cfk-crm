<?php

namespace App\Http\Requests\Admin\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateContact extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.contact.edit', $this->contact);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['sometimes', 'string'],
            'lastname' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', Rule::unique('contacts', 'email')->ignore($this->contact->getKey(), $this->contact->getKeyName()), 'string'],
            'prefered_language' => ['sometimes', 'string'],
            'newsletter' => ['sometimes', 'boolean'],
            'title' => ['required'],
            //'source' => ['required'],
            //'categories' => ['required']
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();


        //Add your code for manipulation with request data here

        return $sanitized;
    }

    public function getTitleId()
    {
        if ($this->has('title') && $this->get('title')) {
            return $this->get('title')['id'];
        }
        return null;
    }

    public function getSourceId()
    {
        if($this->has('source') && $this->get('source')) {
            return $this->get('source')['id'];
        }        
        return null;
    }

    public function getCategories(): array
    {
        if ($this->has('categories') && $this->get('categories')) {
            $categories = $this->get('categories');
            return array_column($categories, 'id');
        }
        return [];
    }
}
