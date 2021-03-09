<?php

namespace Jargoud\LaravelApiKey\Http\Requests\Admin\ApiKey;

class UpdateRequest extends StoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        unset($rules[self::ATTRIBUTE_VALUE]);

        return $rules;
    }
}
