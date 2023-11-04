<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests;

use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Enumeration\Tests\Fixtures\HttpRequestMethod;
use Somnambulist\Components\Enumeration\Tests\Fixtures\MixedAccessibilityEnumeration;
use Somnambulist\Components\Enumeration\Tests\Fixtures\Planet;

class FunctionalTest extends TestCase
{
    /**
     * Test basic setup of Fixtures\HttpRequestMethod class.
     */
    public function testHttpRequestMethodSetup()
    {
        $expected = [
            'OPTIONS' => HttpRequestMethod::OPTIONS(),
            'GET'     => HttpRequestMethod::GET(),
            'HEAD'    => HttpRequestMethod::HEAD(),
            'POST'    => HttpRequestMethod::POST(),
            'PUT'     => HttpRequestMethod::PUT(),
            'DELETE'  => HttpRequestMethod::DELETE(),
            'TRACE'   => HttpRequestMethod::TRACE(),
            'CONNECT' => HttpRequestMethod::CONNECT(),
        ];

        $this->assertSame($expected, HttpRequestMethod::members());
    }

    /**
     * "[The Fixtures\HttpRequestMethod class] can now be used in a type hint to easily
     *  accept any valid HTTP request method".
     */
    public function testHttpRequestMethodAcceptAll()
    {
        $passedMethods = [];
        $handleHttpRequest = function (HttpRequestMethod $method, $url, $body) use (&$passedMethods) {
            $passedMethods[] = $method;
        };
        $handleHttpRequest(HttpRequestMethod::OPTIONS(), 'http://example.org/', null);
        $handleHttpRequest(HttpRequestMethod::GET(), 'http://example.org/', null);
        $handleHttpRequest(HttpRequestMethod::HEAD(), 'http://example.org/', null);
        $handleHttpRequest(HttpRequestMethod::POST(), 'http://example.org/', 'foo');
        $handleHttpRequest(HttpRequestMethod::PUT(), 'http://example.org/', 'foo');
        $handleHttpRequest(HttpRequestMethod::DELETE(), 'http://example.org/', null);
        $handleHttpRequest(HttpRequestMethod::TRACE(), 'http://example.org/', null);
        $handleHttpRequest(HttpRequestMethod::CONNECT(), 'http://example.org/', null);
        $expected = [
            HttpRequestMethod::OPTIONS(),
            HttpRequestMethod::GET(),
            HttpRequestMethod::HEAD(),
            HttpRequestMethod::POST(),
            HttpRequestMethod::PUT(),
            HttpRequestMethod::DELETE(),
            HttpRequestMethod::TRACE(),
            HttpRequestMethod::CONNECT(),
        ];

        $this->assertSame($expected, $passedMethods);
    }

    /**
     * "[The fact that enumeration members are singleton instances] means that
     *  strict comparison (===) can be used to determine which member has been
     *  passed to a function".
     */
    public function testHttpRequestMethodStrictComparison()
    {
        $get = HttpRequestMethod::GET();
        $post = HttpRequestMethod::POST();

        $this->assertTrue($get === HttpRequestMethod::GET());
        $this->assertTrue($post === HttpRequestMethod::POST());
        $this->assertFalse($get === HttpRequestMethod::POST());
        $this->assertFalse($post === HttpRequestMethod::GET());
        $this->assertFalse($get === $post);
    }

    /**
     * Test basic setup of Fixtures\Planet class.
     */
    public function testPlanetSetup()
    {
        $expected = [
            'MERCURY' => Planet::MERCURY(),
            'VENUS'   => Planet::VENUS(),
            'EARTH'   => Planet::EARTH(),
            'MARS'    => Planet::MARS(),
            'JUPITER' => Planet::JUPITER(),
            'SATURN'  => Planet::SATURN(),
            'URANUS'  => Planet::URANUS(),
            'NEPTUNE' => Planet::NEPTUNE(),
        ];

        $this->assertSame($expected, Planet::members());
    }

    /**
     * Test output from the example script for the Fixtures\Planet class.
     */
    public function testPlanetExampleScriptOutput()
    {
        ob_start();
        $earthWeight = 175;
        $mass = $earthWeight / Planet::EARTH()->surfaceGravity();

        foreach (Planet::members() as $planet) {
            // modified slightly to avoid floating point precision issues causing failing test
            echo sprintf(
                'Your weight on %s is %0.0f' . PHP_EOL,
                $planet,
                $planet->surfaceWeight($mass)
            );
        }

        $actual = ob_get_clean();
        $expected =
            'Your weight on MERCURY is 66' . PHP_EOL
            . 'Your weight on VENUS is 158' . PHP_EOL
            . 'Your weight on EARTH is 175' . PHP_EOL
            . 'Your weight on MARS is 66' . PHP_EOL
            . 'Your weight on JUPITER is 443' . PHP_EOL
            . 'Your weight on SATURN is 187' . PHP_EOL
            . 'Your weight on URANUS is 158' . PHP_EOL
            . 'Your weight on NEPTUNE is 199' . PHP_EOL;

        $this->assertSame($expected, $actual);
    }

    /**
     * Tests that only public constants are included as enumeration members.
     */
    public function testMixedAccessibility()
    {
        $members = MixedAccessibilityEnumeration::members();

        self::assertCount(2, $members);
        self::assertArrayHasKey(MixedAccessibilityEnumeration::IMPLICIT_PUBLIC, $members);
        self::assertArrayHasKey(MixedAccessibilityEnumeration::EXPLICIT_PUBLIC, $members);
    }
}
