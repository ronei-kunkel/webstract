<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Web\FakeContent;

use RoneiKunkel\Webstract\Web\Content;

final class FakeContent extends Content
{
	public function __construct(
		public readonly array $list,
		public readonly string $paragraph,
	) {}

	public function tabTitle(): string
	{
		return 'Content Tab Title';
	}

	public function contentTitle(): string
	{
		return 'Content Title';
	}

	public function cssPath(): ?string
	{
		return __DIR__ . '/FakeContent.css';
	}

	public function jsPath(): ?string
	{
		return __DIR__ . '/FakeContent.js';
	}

	public function htmlPath(): string
	{
		return __DIR__ . '/FakeContent.html';
	}
}
