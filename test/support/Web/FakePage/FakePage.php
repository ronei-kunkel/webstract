<?php

declare(strict_types=1);

namespace Test\Support\Web\FakePage;

use Webstract\Web\Page;

final class FakePage extends Page
{
	public function tabTitle(): string
	{
		return "Tab title page template";
	}

	public function cssPath(): string
	{
		return __DIR__ . '/FakePage.css';
	}

	public function jsPath(): string
	{
		return __DIR__ . '/FakePage.js';
	}

	public function htmlPath(): string
	{
		return __DIR__ . '/FakePage.html';
	}
}
