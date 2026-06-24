<?php

declare(strict_types=1);

namespace Test\Web;

use Test\Support\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;
use Test\TestCase;

class AsyncComponentTest extends TestCase
{
	public function test_AsyncComponentKeepsSpecificContextKeyAndComponentContextBehavior(): void
	{
		$component = new FakeNonRenderableAsyncComponent('title');
		$context = $component->context();

		$this->assertSame('component', $component->contextKey());
		$this->assertArrayHasKey($component->contextKey(), $context);
		$this->assertSame($component, $context[$component->contextKey()]);
	}

	public function test_AsyncComponentMaintainsSpecificShouldRenderedBehavior(): void
	{
		$component = new FakeNonRenderableAsyncComponent('title');

		$this->assertFalse($component->shouldRendered());
	}

	public function test_AssetPathsRespectNullableAndExpectedFormat(): void
	{
		$component = new FakeNonRenderableAsyncComponent('title');

		$this->assertNull($component->cssPath());
		$this->assertNull($component->jsPath());
		$this->assertStringEndsWith('.html', $component->htmlPath());
	}
}
