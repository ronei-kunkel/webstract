<?php

declare(strict_types=1);

namespace Test\Session;

use Test\Support\Session\FakeSessionKey;
use Test\TestCase;
use Webstract\Session\AbstractedSessionHandler;
use Webstract\Session\Exception\SessionValueNotFoundException;
use Webstract\Session\SuperglobalSessionStorage;

class AbstractedSessionHandlerTest extends TestCase
{
	private AbstractedSessionHandler $handler;

	protected function setUp(): void
	{
		parent::setUp();
		$this->handler = new AbstractedSessionHandler(new SuperglobalSessionStorage());
		$_SESSION = [];
	}

	public function test_SetAndGet_ShouldReadValueFromSession(): void
	{
		$key = new FakeSessionKey('foo');

		$this->handler->set($key, 'bar');

		$this->assertSame('bar', $this->handler->get($key));
	}

	public function test_Get_ShouldThrowException_WhenValueDoesNotExist(): void
	{
		$this->expectException(SessionValueNotFoundException::class);
		$this->expectExceptionMessage('Session value `foo` was not found');

		$this->handler->get(new FakeSessionKey('foo'));
	}

	public function test_Consume_ShouldReturnValueAndDeleteIt(): void
	{
		$key = new FakeSessionKey('foo');
		$this->handler->set($key, 'bar');

		$this->assertSame('bar', $this->handler->consume($key));
		$this->assertFalse($this->handler->has($key));
	}

	public function test_ConsumeOrDefault_ShouldReturnDefault_WhenValueDoesNotExist(): void
	{
		$key = new FakeSessionKey('foo');

		$this->assertSame('fallback', $this->handler->consumeOrDefault($key, 'fallback'));
		$this->assertFalse($this->handler->has($key));
	}

	protected function tearDown(): void
	{
		$_SESSION = [];
		parent::tearDown();
	}
}
