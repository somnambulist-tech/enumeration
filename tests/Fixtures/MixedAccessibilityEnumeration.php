<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

use Somnambulist\Components\Enumeration\AbstractEnumeration;

/**
 * An enumeration whose members have different accessibility levels.
 *
 * @method static IMPLICIT_PUBLIC()
 * @method static EXPLICIT_PUBLIC()
 */
final class MixedAccessibilityEnumeration extends AbstractEnumeration
{
    const IMPLICIT_PUBLIC = 'IMPLICIT_PUBLIC';
    public const EXPLICIT_PUBLIC = 'EXPLICIT_PUBLIC';
    protected const PROTECTED = 'PROTECTED';
    private const PRIVATE = 'PRIVATE';
}
