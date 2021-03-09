<?php

namespace Jargoud\LaravelApiKey\Models\Backpack;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Jargoud\LaravelApiKey\Models\ApiKey as BaseApiKey;

class ApiKey extends BaseApiKey
{
    use CrudTrait;
}
