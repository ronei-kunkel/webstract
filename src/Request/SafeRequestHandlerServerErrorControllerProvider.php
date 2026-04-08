<?php

declare(strict_types=1);

namespace Webstract\Request;

use Webstract\Controller\ApiController;

abstract class SafeRequestHandlerServerErrorControllerProvider
{
	/** @return class-string<ApiController> */
	abstract public function getJsonServerErrorController(): string;

	/** @return class-string<PageController> */
	abstract public function getHtmlServerErrorController(): string;
}
