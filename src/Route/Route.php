<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Route;

use RuntimeException;

abstract class Route implements RouteDefinition, RoutePathTemplate
{
	protected array $definedParams = [];

	public function withPathParams(...$params): self
	{
		$this->definedParams = [...$params];
		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return string
	 */
	public function renderPath(): string
	{
		if (empty($this->definedParams)) {
			throw new RuntimeException(
				"Try render route before define params with \$this->withParams(...\$params) method"
			);
		}

		$format = $this->getPathFormat();
		$resources = preg_match_all('/%/', $format);

		if ($resources > count($this->definedParams)) {
			$patternArray = explode('|', str_repeat('%s|', $resources - count($this->definedParams)));
			$fallbackValues = array_merge([...$this->definedParams], $patternArray);
			throw new RuntimeException(
				'Route has unfilled resources. Output: ' . sprintf($format, ...$fallbackValues)
			);
		}

		return sprintf($format, ...$this->definedParams);
	}
}
