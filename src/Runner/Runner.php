<?php

declare(strict_types=1);

namespace Webstract\Runner;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\create;
use function DI\get;

abstract class Runner
{
	/** @var Bind[] */
	protected array $binds;
	protected readonly ContainerInterface $container;

	abstract public function execute(): void;

	public function withBinds(Bind ...$bind): self
	{
		$this->container = new ContainerBuilder()
			->useAttributes(true)
			->addDefinitions(
				$this->buildDefinitions([...$bind])
			)->build();

		return $this;
	}

	private function buildDefinitions(array $binds): array
	{
		$definitions = [];

		foreach ($binds as $bind) {
			if ($bind->implementationIsObject()) {
				$definitions[$bind->getInterface()] = $bind->getImplementation();
				continue;
			}

			if ($bind->hasProperties()) {
				$parameters = [];
				foreach($bind->getProperties() as $property) {
					$parameters[] = get($property);
				}
				$definitions[$bind->getInterface()] = create($bind->getImplementation())->constructor(
					...$parameters
				);
				continue;
			}

			$definitions[$bind->getInterface()] = create($bind->getImplementation());
		}

		return $definitions;
	}
}
