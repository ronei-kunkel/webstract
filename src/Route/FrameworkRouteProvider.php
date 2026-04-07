<?php

declare(strict_types=1);

namespace Webstract\Route;

use Webstract\Web\Module\Health\HealthRoute;
use Webstract\Web\Module\StorageManager\Route\StorageManagerExpandFolderRoute;
use Webstract\Web\Module\StorageManager\Route\StorageManagerPageRouter;
use Webstract\Web\Module\WebTerminal\Route\WebTerminalPageRoute;
use Webstract\Web\Module\WebTerminal\Route\WebTerminalSendCommandActionRoute;
use Webstract\Route\RouteProviderInterface;
use Webstract\Route\RouteDefinition;

final class FrameworkRouteProvider implements RouteProviderInterface
{
	/** @return RouteDefinition[] */
	public function provideRouteDefinitions(): array
	{
		return [
			// new HealthRoute(),

			// new WebTerminalPageRoute(),
			// new WebTerminalSendCommandActionRoute(),

			// new StorageManagerPageRouter(),
			// new StorageManagerExpandFolderRoute(),
		];
	}
}
