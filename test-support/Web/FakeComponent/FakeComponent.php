<?php

declare(strict_types=1);

namespace Test\Support\Web\FakeComponent;

use Webstract\Web\Component;

class FakeComponent extends Component
{
	public function __construct(
		public readonly string $text
	) {}

	public function contextKey(): string
	{
		return 'fakeComponent';
	}

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
		return __DIR__ . '/FakeComponent.twig';
	}
}
