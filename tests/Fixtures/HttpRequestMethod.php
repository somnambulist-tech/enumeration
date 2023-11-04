<?php declare(strict_types=1);

namespace Somnambulist\Components\Enumeration\Tests\Fixtures;

use Somnambulist\Components\Enumeration\AbstractEnumeration;

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
