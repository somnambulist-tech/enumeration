<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

class ValidValueMultiton extends TestValueMultiton
{
    public static function resetCalls()
    {
        static::$calls = array();
    }

    public static function calls()
    {
        return static::$calls;
    }

    protected static function initializeMembers()
    {
        parent::initializeMembers();

        new static('BAZ', 'zab');
    }

    protected static $calls = array();
}
