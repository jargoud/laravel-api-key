<?php

namespace Jargoud\LaravelApiKey\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jargoud\LaravelApiKey\Enums\ApiKey\Restriction;

class ApiKey extends Base\ApiKey
{
    public function setValueAttribute(string $value): void
    {
        $this->attributes[self::COLUMN_PREFIX] = substr($value, 0, 8);
        $this->attributes[self::COLUMN_VALUE] = hash('sha256', $value);
    }

    public function validate(Request $request): bool
    {
        switch ($this->restriction) {
            case Restriction::NONE:
                return true;

            case Restriction::REFERER:
                return $this->validateReferer(
                    $request->headers->get('referer') ?? ''
                );

            case Restriction::IP:
                return $this->validateIp(
                    $request->ip()
                );
        }

        return false;
    }

    public function validateReferer(string $referer): bool
    {
        foreach ($this->referer as $value) {
            if (Str::startsWith($referer, $value)) {
                return true;
            }
        }

        return false;
    }

    public function validateIp(string $ip): bool
    {
        foreach ($this->ip as $value) {
            if ($ip === $value) {
                return true;
            }
        }

        return false;
    }
}
