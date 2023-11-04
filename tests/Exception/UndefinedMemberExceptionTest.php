<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Enumeration\Exception\UndefinedMemberException;

/**
 * @covers \Somnambulist\Components\Enumeration\Exception\UndefinedMemberException
 * @covers \Somnambulist\Components\Enumeration\Exception\AbstractUndefinedMemberException
 */
class UndefinedMemberExceptionTest extends TestCase
{
    public function testException()
    {
        $className = 'foo';
        $property = 'bar';
        $value = 'baz';
        $previous = new Exception();
        $exception = new UndefinedMemberException($className, $property, $value, $previous);
        $expectedMessage = "No member with bar equal to 'baz' defined in class 'foo'.";

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($className, $exception->className());
        $this->assertSame($property, $exception->property());
        $this->assertSame($value, $exception->value());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
