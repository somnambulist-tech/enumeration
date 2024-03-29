# Somnambulist Enumeration

[![GitHub Actions Build Status](https://img.shields.io/github/actions/workflow/status/somnambulist-tech/enumeration/tests.yml?logo=github&branch=main)](https://github.com/somnambulist-tech/enumeration/actions?query=workflow%3Atests)
[![Issues](https://img.shields.io/github/issues/somnambulist-tech/enumeration?logo=github)](https://github.com/somnambulist-tech/enumeration/issues)
[![License](https://img.shields.io/github/license/somnambulist-tech/enumeration?logo=github)](https://github.com/somnambulist-tech/enumeration/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/somnambulist/enumeration?logo=php&logoColor=white)](https://packagist.org/packages/somnambulist/enumeration)
[![Current Version](https://img.shields.io/packagist/v/somnambulist/enumeration?logo=packagist&logoColor=white)](https://packagist.org/packages/somnambulist/enumeration)

Provides extended enumeration support for PHP beyond what the PHP 8+ enum allows for. This project is a
continuation of [eloquent/enumeration](https://github.com/eloquent/enumeration).

## Installation

- Available as [Composer] package [somnambulist/enumeration].

[composer]: http://getcomposer.org/
[somnambulist/enumeration]: https://packagist.org/packages/somnambulist/enumeration

## What is an Enumeration?

In terms of software development, an enumeration (or "enumerated type") is
essentially a fixed set of values. These values are called "members" or
"elements".

An enumeration is used in circumstances where it is desirable to allow an
argument to be only one of a particular set of values, and where anything else
is considered invalid.

## A basic example

*Enumeration* can be used like [C++ enumerated types]. Here is an example,
representing a set of HTTP request methods:

```php
use Somnambulist\Enumeration\AbstractEnumeration;

final class HttpRequestMethod extends AbstractEnumeration
{
    const OPTIONS = 'OPTIONS';
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const TRACE = 'TRACE';
    const CONNECT = 'CONNECT';
}
```

This class can now be used in a type hint to easily accept any valid HTTP
request method:

```php
function handleHttpRequest(HttpRequestMethod $method, $url, $body = null)
{
    // handle request...
}
```

__Note:__ for simple enumerations, it is recommended to use PHPs native backed enums.

[c++ enumerated types]: https://en.wikipedia.org/wiki/Enumerated_type#C.2B.2B

## Accessing enumeration members

Members are accessed by static method calls, like so:

```php
handleHttpRequest(HttpRequestMethod::GET(), 'http://example.org/');
handleHttpRequest(HttpRequestMethod::POST(), 'http://example.org/', 'foo=bar&baz=qux');
```

For each member of the enumeration, a single instance of the enumeration class
is instantiated (that is, an instance of `HttpRequestMethod` in the above
example). This means that strict comparison (===) can be used to determine
which member has been passed to a function:

```php
function handleHttpRequest(HttpRequestMethod $method, $url, $body = null)
{
    if ($method === HttpRequestMethod::POST()) {
        // handle POST requests...
    } else {
        // handle other requests...
    }
}
```

## Java-style enumerations

[Java's enum types] have slightly more functionality than C++ enumerated types.
They can have additional properties and/or methods, and are really just a
specialised kind of class where there are a fixed set of instances.

This is sometimes called the [Multiton] pattern, and in fact, all enumerations
in this implementation are Multitons. The `AbstractEnumeration` class simply
defines its members based upon class constants.

Here is an example borrowed from the Java documentation for its enum types. The
following multiton describes all the planets in our solar system, including
their masses and radii:

```php
use Somnambulist\Enumeration\AbstractMultiton;

final class Planet extends AbstractMultiton
{
    /**
     * Universal gravitational constant.
     *
     * @var float
     */
    const G = 6.67300E-11;

    /**
     * @return float
     */
    public function surfaceGravity()
    {
        return self::G * $this->mass / ($this->radius * $this->radius);
    }

    /**
     * @param float $otherMass
     *
     * @return float
     */
    public function surfaceWeight($otherMass)
    {
        return $otherMass * $this->surfaceGravity();
    }

    protected static function initializeMembers()
    {
        new static('MERCURY', 3.302e23,  2.4397e6);
        new static('VENUS',   4.869e24,  6.0518e6);
        new static('EARTH',   5.9742e24, 6.37814e6);
        new static('MARS',    6.4191e23, 3.3972e6);
        new static('JUPITER', 1.8987e27, 7.1492e7);
        new static('SATURN',  5.6851e26, 6.0268e7);
        new static('URANUS',  8.6849e25, 2.5559e7);
        new static('NEPTUNE', 1.0244e26, 2.4764e7);
        // Pluto will always be a planet to me!
        new static('PLUTO',   1.31e22,   1.180e6);
    }

    /**
     * @param string $key
     * @param float  $mass
     * @param float  $radius
     */
    protected function __construct($key, $mass, $radius)
    {
        parent::__construct($key);

        $this->mass = $mass;
        $this->radius = $radius;
    }

    private $mass;
    private $radius;
}
```

The above class can be used to take a known weight on earth (in any unit) and
calculate the weight on all the planets (in the same unit):

```php
$earthWeight = 175;
$mass = $earthWeight / Planet::EARTH()->surfaceGravity();

foreach (Planet::members() as $planet) {
    echo sprintf(
        'Your weight on %s is %f' . PHP_EOL,
        $planet,
        $planet->surfaceWeight($mass)
    );
}
```

If the above script is executed, it will produce something like the following
output:

```
Your weight on MERCURY is 66.107480
Your weight on VENUS is 158.422560
Your weight on EARTH is 175.000000
Your weight on MARS is 66.279359
Your weight on JUPITER is 442.677903
Your weight on SATURN is 186.513785
Your weight on URANUS is 158.424919
Your weight on NEPTUNE is 199.055584
```

[java's enum types]: https://en.wikipedia.org/wiki/Enumerated_type#Java
[multiton]: http://en.wikipedia.org/wiki/Multiton_pattern

## Enumerations and class inheritance

When an enumeration is defined, the intent is usually to define a set of valid
values that should not change, at least within the lifetime of a program's
execution.

Since PHP has no in-built support for enumerations, this library implements them
as regular PHP classes. Classes, however, allow for much more extensibility than
is desirable in a true enumeration.

For example, a naive enumeration implementation might allow a developer to
extend the `HttpRequestMethod` class from the examples above (assuming the
`final` keyword is removed):

```php
class CustomHttpMethod extends HttpRequestMethod
{
    const PATCH = 'PATCH';
}
```

The problem with this scenario is that all the code written to expect only the
HTTP methods defined in `HttpRequestMethod` is now compromised. Anybody can
extend `HttpRequestMethod` to add custom values, essentially voiding the reason
for defining `HttpRequestMethod` in the first place.

This library provides built-in protection from these kinds of circumstances.
Attempting to define an enumeration that extends another enumeration will result
in an exception being thrown, unless the 'base' enumeration is abstract.

### Abstract enumerations

Assuming that there really is a need to extend `HttpRequestMethod`, the way to
go about it is to define an abstract base class, then extend this class to
create the desired concrete enumerations:

```php
use Somnambulist\Enumeration\AbstractEnumeration;

abstract class AbstractHttpRequestMethod extends AbstractEnumeration
{
    const OPTIONS = 'OPTIONS';
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const TRACE = 'TRACE';
    const CONNECT = 'CONNECT';
}

final class HttpRequestMethod extends AbstractHttpRequestMethod {}

final class CustomHttpMethod extends AbstractHttpRequestMethod
{
    const PATCH = 'PATCH';
}
```

In this way, when a developer uses a type hint for `HttpRequestMethod`, there is
no chance they will ever receive the 'PATCH' method:

```php
function handleHttpRequest(HttpRequestMethod $method, $url, $body = null)
{
    // only handles normal requests...
}

function handleCustomHttpRequest(
    CustomHttpRequestMethod $method,
    $url,
    $body = null
) {
    // handles normal requests, and custom requests...
}
```
