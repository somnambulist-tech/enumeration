<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

use Somnambulist\Components\Enumeration\AbstractEnumeration;

abstract class TestEnumeration extends AbstractEnumeration
{
    const FOO = 'oof';
    const BAR = 'rab';
}
