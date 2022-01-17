<?php

namespace App\Http\Requests\Admin\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreContact extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.contact.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('contacts', 'email'), 'string'],
            'prefered_language' => ['required', 'string'],
            'newsletter' => ['required', 'boolean'],
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
        if($this->has('title') && $this->get('title')) {
            return $this->get('title')['id'];
        }
        return 1;
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
