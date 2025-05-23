<?php

declare(strict_types=1);

namespace Webstract\TemplateEngine;

interface TemplateEngineRenderer
{
	public function render(string $path, array $context): string;
}
