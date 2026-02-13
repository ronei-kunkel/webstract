<?php

declare(strict_types=1);

namespace Test\Support\Web\FakePage;

use Test\Support\Web\FakeComponent\FakeComponent;
use Webstract\Web\Content;
use Webstract\Web\Page;

final class FakePage extends Page
{
	public function __construct(
		Content $content,
		public readonly FakeComponent $fakeComponent
	) {
		parent::__construct($content);
	}

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

	public function getContext(): array
	{
		return [
			$this->contextKey() => $this,
			$this->content->contextKey() => $this->content,
			$this->fakeComponent->contextKey() => $this->fakeComponent,
		];
	}
}
