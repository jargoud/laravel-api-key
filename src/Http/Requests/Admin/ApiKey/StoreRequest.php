<?php

namespace Jargoud\LaravelApiKey\Http\Requests\Admin\ApiKey;

use BenSampo\Enum\Rules\EnumValue;
use Jargoud\LaravelApiKey\Enums\ApiKey\Restriction;
use Jargoud\LaravelApiKey\Http\Requests\Admin\Request as BaseRequest;

class StoreRequest extends BaseRequest
{
    public const ATTRIBUTE_NAME = 'name';
    public const ATTRIBUTE_VALUE = 'value';
    public const ATTRIBUTE_RESTRICTION = 'restriction';
    public const ATTRIBUTE_REFERER = 'referer';
    public const ATTRIBUTE_IP = 'ip';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            self::ATTRIBUTE_NAME => $this->getNameRules(),
            self::ATTRIBUTE_VALUE => $this->getValueRules(),
            self::ATTRIBUTE_RESTRICTION => $this->getRestrictionRules(),
            self::ATTRIBUTE_REFERER => $this->getRefererRules(),
            self::ATTRIBUTE_IP => $this->getIpRules(),
        ];
    }

    protected function getNameRules(): array
    {
        return ['required', 'string', 'max:191'];
    }

    protected function getValueRules(): array
    {
        return ['required', 'string', 'uuid'];
    }

    protected function getRestrictionRules(): array
    {
        return [
            'required',
            new EnumValue(Restriction::class),
        ];
    }

    protected function getRefererRules(): array
    {
        return [
            sprintf("required_if:%s,%s", self::ATTRIBUTE_RESTRICTION, Restriction::REFERER),
            'nullable',
        ];
    }

    protected function getIpRules(): array
    {
        return [
            sprintf("required_if:%s,%s", self::ATTRIBUTE_RESTRICTION, Restriction::IP),
            'nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            self::ATTRIBUTE_REFERER => $this->prepareArray($this->input(self::ATTRIBUTE_REFERER)),
            self::ATTRIBUTE_IP => $this->prepareArray($this->input(self::ATTRIBUTE_IP)),
        ]);
    }

    protected function prepareArray(?string $input): ?array
    {
        return empty($input)
            ? null
            : array_unique(
                array_map(
                    'trim',
                    explode(',', $input)
                )
            );
    }
}
