<?php

declare(strict_types=1);

namespace Test\Web;

use Test\Support\Web\FakeComponent\FakeComponent;
use Test\Support\Web\FakeContent\FakeContent;
use Test\Support\Web\FakePage\FakePage;
use Test\TestCase;

class PageTest extends TestCase
{
	public function test_PageExposesContentCompositionWithoutBreakingContentContextContract(): void
	{
		$content = new FakeContent(['1', '2'], 'paragraph');
		$page = new FakePage($content, new FakeComponent('text'));
		$context = $page->context();

		$this->assertArrayHasKey('page', $context);
		$this->assertSame($page, $context['page']);

		$this->assertArrayHasKey($content->contextKey(), $context);
		$this->assertSame($content, $context[$content->contextKey()]);
		$this->assertSame($content->context(), [$content->contextKey() => $context[$content->contextKey()]]);
	}

	public function test_PageAssetPathsHaveExpectedFormat(): void
	{
		$page = new FakePage(new FakeContent(['1', '2'], 'paragraph'), new FakeComponent('text'));

		$this->assertStringEndsWith('.css', $page->cssPath());
		$this->assertStringEndsWith('.js', $page->jsPath());
		$this->assertStringEndsWith('.html', $page->htmlPath());
	}
}
