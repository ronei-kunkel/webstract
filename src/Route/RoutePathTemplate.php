<?php

declare(strict_types=1);

namespace Webstract\Route;

use Webstract\Route\Exception\RoutePathParamsUnexpectedParamValues;

abstract class RoutePathTemplate
{
	private array $definedParams = [];

	abstract public function getPathFormat(): string;

	/**
	 * @throws RoutePathParamsUnexpectedParamValues
	 * @return self
	 */
	final public function withPathParams(int|string ...$params): self
	{
		$pathFormat = $this->getPathFormat();
		$numberOfParams = count([...$params]);
		$numberOfPlaceholders = preg_match_all('/%/', $pathFormat);

		if ($numberOfParams !== $numberOfPlaceholders) {
			throw new RoutePathParamsUnexpectedParamValues($pathFormat, $numberOfPlaceholders, $numberOfParams);
		}

		$this->definedParams = [...$params];
		return $this;
	}

	/** @return string */
	final public function renderPath(): string
	{
		return sprintf($this->getPathFormat(), ...$this->definedParams);
	}
}
