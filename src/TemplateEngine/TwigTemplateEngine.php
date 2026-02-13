<?php

declare(strict_types=1);

namespace Webstract\TemplateEngine;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Webstract\TemplateEngine\TemplateEngineRenderer;

final class TwigTemplateEngine implements TemplateEngineRenderer
{
	private static ?Environment $engine;

	public function render(string $name, array $context): string
	{
		return $this->buildEngine()->render($name, $context);
	}

	private function buildEngine(): Environment
	{
		self::$engine ?? self::$engine = new Environment(
			new FilesystemLoader('/', '/'),
			$this->getOptions()
		);
		self::$engine->addExtension(new DebugExtension());

		return self::$engine;
	}

	private function getOptions(): array
	{
		return [
			'cache' => false,
			'use_yield' => true,
			'debug' => true,
			// 'strict_variables' => true
		];
	}
}
