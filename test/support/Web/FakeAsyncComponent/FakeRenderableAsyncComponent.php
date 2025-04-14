<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Web\FakeAsyncComponent;

use RoneiKunkel\Webstract\Web\AsyncComponent;

final class FakeRenderableAsyncComponent extends AsyncComponent
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
		return __DIR__ . '/FakeRenderableAsyncComponent.html';
	}

	public function shouldRendered(): bool
	{
		return true;
	}
}
