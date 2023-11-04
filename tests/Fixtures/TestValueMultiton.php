<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

use Somnambulist\Components\Enumeration\AbstractValueMultiton;

abstract class TestValueMultiton extends AbstractValueMultiton
{
    protected static function initializeMembers()
    {
        parent::initializeMembers();

        static::$calls[] = array(
            get_called_class() . '::' . __FUNCTION__,
            func_get_args(),
        );

        new static('FOO', 'oof');
        new static('BAR', 'rab');
    }
}
