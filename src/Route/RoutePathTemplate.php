<?php

declare(strict_types=1);

namespace Webstract\Route;

use RuntimeException;

abstract class RoutePathTemplate
{
	private array $definedParams = [];

	abstract public function getPathFormat(): string;

	final public function withPathParams(int|string ...$params): self
	{
		$this->definedParams = [...$params];
		return $this;
	}

	/**
	 * @throws \RuntimeException
	 * @return string
	 */
	final public function renderPath(): string
	{
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
