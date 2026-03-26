<?php

declare(strict_types=1);

namespace Test\Session;

use Test\TestCase;
use Webstract\Session\SuperglobalSessionStorage;

class SuperglobalSessionStorageTest extends TestCase
{
	private SuperglobalSessionStorage $storage;

	protected function setUp(): void
	{
		parent::setUp();
		$this->storage = new SuperglobalSessionStorage();
		$_SESSION = [];
	}

	public function test_SetGetHasAndDelete_ShouldOperateOnSuperglobalSession(): void
	{
		$this->storage->set('foo', 'bar');

		$this->assertTrue($this->storage->has('foo'));
		$this->assertSame('bar', $this->storage->get('foo'));

		$this->storage->delete('foo');
		$this->assertFalse($this->storage->has('foo'));
	}

	public function test_DestroySession_ShouldClearSessionValues(): void
	{
		$this->storage->set('foo', 'bar');

		$this->storage->destroySession();

		$this->assertSame([], $_SESSION);
	}

	protected function tearDown(): void
	{
		$_SESSION = [];
		parent::tearDown();
	}
}
