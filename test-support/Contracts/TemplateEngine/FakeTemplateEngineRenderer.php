<?php

declare(strict_types=1);

namespace Test\Support\Contracts\TemplateEngine;

use RuntimeException;
use Webstract\TemplateEngine\TemplateEngineRenderer;

class FakeTemplateEngineRenderer implements TemplateEngineRenderer
{
	public bool $shouldThrow = false;

	public function render(string $path, array $context): string
	{
		if ($this->shouldThrow) {
			throw new RuntimeException('template render failed');
		}

		return sprintf('%s::%s', $path, json_encode($context));
	}
}
