<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\TemplateEngine;

use RoneiKunkel\Webstract\TemplateEngine\TemplateEngineRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigTemplateEngineRenderer implements TemplateEngineRenderer
{
	private static ?Environment $engine;

	public function render(string $name, array $context): string
	{
		return $this->buildEngine()->render($name, $context);
	}

	private function buildEngine(): Environment
	{
		return self::$engine ?? self::$engine = new Environment(
			new FilesystemLoader('/', '/'),
			$this->getOptions()
		);
	}

	private function getOptions(): array
	{
		return [
			'cache'     => false,
			'use_yield' => true,
			'debug'     => true,
		];
	}
}
