<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Page;

use RoneiKunkel\Webstract\Page\PageTemplate;

final class FakePageTemplate extends PageTemplate
{
	public function __construct(
		public readonly string $test
	) {}

	public function tabTitle(): string
	{
		return "Tab title page template";
	}

	public function cssPath(): string
	{
		return __DIR__ . '/FakePageTemplate.css';
	}

	public function jsPath(): string
	{
		return __DIR__ . '/FakePageTemplate.js';
	}

	public function htmlPath(): string
	{
		return __DIR__ . '/FakePageTemplate.html';
	}
}
