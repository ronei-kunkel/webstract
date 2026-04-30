<?php

declare(strict_types=1);

namespace Test\Storage;

use Test\TestCase;
use Webstract\Storage\Path;

final class PathTest extends TestCase
{
    public function testItResolvesPreviousPathHierarchy(): void
    {
        self::assertNull(Path::ROOT->getPrevious());
        self::assertSame(Path::ROOT, Path::BACKUPS->getPrevious());
        self::assertSame(Path::PROD, Path::PROD_IMAGES->getPrevious());
        self::assertSame(Path::SANDBOX, Path::SANDBOX_BACKUPS->getPrevious());
    }
}
