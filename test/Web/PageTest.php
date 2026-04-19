<?php

declare(strict_types=1);

use Test\Support\Web\FakeComponent\FakeComponent;
use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;
use Test\TestCase;

class PageTest extends TestCase
{
	public function test_ContextShouldReturnExpectedValue(): void
	{
		$expectedContext = [
			'page' => new FakePage(new FakeContent(['1','2'], 'paragraph'), new FakeComponent('text')),
			'content' => new FakeContent(['1','2'], 'paragraph'),
			'fakeComponent' => new FakeComponent('text'),
		];
		$page = new FakePage(new FakeContent(['1','2'], 'paragraph'), new FakeComponent('text'));

		$this->assertEquals($expectedContext, $page->context());
	}
}
