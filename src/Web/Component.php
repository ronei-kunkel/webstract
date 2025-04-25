<?php

declare(strict_types=1);

namespace Webstract\Web;

abstract class Component
{
	abstract public function cssPath(): ?string;
	abstract public function jsPath(): ?string;
	abstract public function htmlPath(): string;

	/** Must diferent from each concrete component */
	abstract public function contextKey(): string;
}
