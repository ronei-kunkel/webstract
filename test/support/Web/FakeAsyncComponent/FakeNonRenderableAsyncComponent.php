<?php

declare(strict_types=1);

namespace Test\Support\Web\FakeAsyncComponent;

use Webstract\Web\AsyncComponent;

final class FakeNonRenderableAsyncComponent extends AsyncComponent
{
	public function __construct(
		public readonly string $title
	) {}

	public function cssPath(): ?string
	{
		return null;
	}

	public function jsPath(): ?string
	{
		return null;
	}

	public function htmlPath(): string
	{
		return __DIR__ . '/FakeNonRenderableAsyncComponent.html';
	}

	public function shouldRendered(): bool
	{
		return false;
	}
}
