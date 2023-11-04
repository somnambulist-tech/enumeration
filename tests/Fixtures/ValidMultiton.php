<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

class ValidMultiton extends TestMultiton
{
    public static function resetCalls()
    {
        static::$calls = array();
    }

    public static function calls()
    {
        return static::$calls;
    }

    public function value()
    {
        return $this->value;
    }

    protected static function initializeMembers()
    {
        parent::initializeMembers();

        new static('BAZ', 'zab');
    }

    protected function __construct($key, $value)
    {
        parent::__construct($key);

        $this->value = $value;
    }

    protected static $calls = array();
    protected $value;
}
