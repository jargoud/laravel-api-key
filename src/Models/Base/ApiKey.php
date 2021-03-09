<?php

namespace Jargoud\LaravelApiKey\Models\Base;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class ApiKey extends Authenticatable
{
    use SoftDeletes;

    public const COLUMN_NAME = 'name';
    public const COLUMN_PREFIX = 'prefix';
    public const COLUMN_VALUE = 'value';
    public const COLUMN_RESTRICTION = 'restriction';
    public const COLUMN_REFERER = 'referer';
    public const COLUMN_IP = 'ip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_PREFIX,
        self::COLUMN_VALUE,
        self::COLUMN_RESTRICTION,
        self::COLUMN_REFERER,
        self::COLUMN_IP,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        self::COLUMN_REFERER => 'array',
        self::COLUMN_IP => 'array',
    ];
}
