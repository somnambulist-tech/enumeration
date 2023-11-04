<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

class InvalidMultiton extends ValidMultiton
{
    protected static function initializeMembers()
    {
        parent::initializeMembers();

        new static('QUX', 'xuq');
    }
}
