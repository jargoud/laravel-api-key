<?php

namespace Jargoud\LaravelApiKey\Enums\ApiKey;

use BenSampo\Enum\Enum;

/**
 * @method static static NONE()
 * @method static static REFERER()
 * @method static static IP()
 */
final class Restriction extends Enum
{
    public const NONE = 'none';
    public const REFERER = 'referer';
    public const IP = 'ip';
}
