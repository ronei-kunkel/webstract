<?php

declare(strict_types=1);

namespace Test\Web;

use Exception;
use Test\Support\Web\FakeContent\FakeContent;
use Test\TestCase;

class ContentTest extends TestCase
{
	public function test_ContextShouldReturnExpectedValues(): void
	{
		$expectedContext = [
			'content' => new FakeContent(['1', '2'], 'paragraph'),
		];
		$content = new FakeContent(['1', '2'], 'paragraph');

		$this->assertEquals($expectedContext, $content->context());
		$this->assertEquals('content', $content->contextKey());
	}
}
