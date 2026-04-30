<?php

declare(strict_types=1);

namespace Test\Session;

use stdClass;
use PHPUnit\Framework\Attributes\Group;
use Test\TestCase;
use Webstract\Session\Exception\SessionValueNotFoundException;
use Webstract\Session\NativeSession;
use Webstract\Session\SessionKeyInterface;

#[Group('infra')]
final class NativeSessionTest extends TestCase
{
    private NativeSession $session;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }

        $_SESSION = [];
        $this->session = new NativeSession();
        $this->session->initSession();
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    public function testItStoresAndReadsScalarAndObjectValues(): void
    {
        $scalarKey = $this->key('token');
        $objectKey = $this->key('user');
        $object = new stdClass();
        $object->name = 'Jane';

        $this->session->set($scalarKey, 'abc123');
        $this->session->set($objectKey, $object);

        self::assertTrue($this->session->has($scalarKey));
        self::assertSame('abc123', $this->session->get($scalarKey));
        self::assertInstanceOf(stdClass::class, $this->session->get($objectKey));
        self::assertSame('Jane', $this->session->get($objectKey)->name);
    }

    public function testItConsumesAndDeletesValues(): void
    {
        $key = $this->key('flash');
        $this->session->set($key, 'saved');

        self::assertSame('saved', $this->session->consume($key));
        self::assertFalse($this->session->has($key));
        self::assertSame('default', $this->session->consumeOrDefault($key, 'default'));
    }

    public function testItThrowsWhenValueIsMissing(): void
    {
        $this->expectException(SessionValueNotFoundException::class);
        $this->session->get($this->key('missing'));
    }

    private function key(string $name): SessionKeyInterface
    {
        return new class($name) implements SessionKeyInterface {
            public function __construct(private readonly string $name) {}
            public function getName(): string { return $this->name; }
        };
    }
}
