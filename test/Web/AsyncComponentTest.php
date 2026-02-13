<?php

declare(strict_types=1);

use Test\Support\Web\FakeAsyncComponent\FakeNonRenderableAsyncComponent;
use Test\TestCase;

use function PHPUnit\Framework\throwException;

class AsyncComponentTest extends TestCase
{
	public function test_ContextShouldReturnExpectedValue(): void
	{
		$expectedContext = [
			'component' => new FakeNonRenderableAsyncComponent('title')
		];
		$component = new FakeNonRenderableAsyncComponent('title');

		$this->assertEquals($expectedContext, $component->getContext());
		$this->assertEquals('component', $component->contextKey());
	}
}
