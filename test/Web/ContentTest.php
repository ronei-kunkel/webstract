<?php

declare(strict_types=1);

namespace Test\Web;

use Test\Support\Web\FakeContent\FakeContent;
use Test\TestCase;

class ContentTest extends TestCase
{
	public function test_ContextAlwaysContainsKeyFromContextKey(): void
	{
		$content = new FakeContent(['1', '2'], 'paragraph');
		$context = $content->context();

		$this->assertArrayHasKey($content->contextKey(), $context);
		$this->assertSame($content, $context[$content->contextKey()]);
	}

	public function test_AssetPathsRespectNullableAndExpectedFormat(): void
	{
		$content = new FakeContent(['1', '2'], 'paragraph');

		$this->assertIsString($content->cssPath());
		$this->assertStringEndsWith('.css', (string) $content->cssPath());

		$this->assertIsString($content->jsPath());
		$this->assertStringEndsWith('.js', (string) $content->jsPath());

		$this->assertIsString($content->htmlPath());
		$this->assertStringEndsWith('.html', $content->htmlPath());
	}
}
