<?php

declare(strict_types=1);

namespace Test\Integration\Storage;

use PHPUnit\Framework\Attributes\Group;
use Test\TestCase;

#[Group('integration')]
final class S3FileHandlerIntegrationTest extends TestCase
{
    public function testS3IntegrationSuiteMustBeEnabledExplicitly(): void
    {
        $this->markTestSkipped('Optional integration suite for S3/network tests. Run only with --group integration and required infra.');
    }
}
