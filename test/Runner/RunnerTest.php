<?php

declare(strict_types=1);

namespace Test\Runner;

use PHPUnit\Framework\Attributes\CoversClass;
use Test\TestCase;
use Webstract\Runner\Bind;
use Webstract\Runner\Runner;

#[CoversClass(Runner::class)]
#[CoversClass(Bind::class)]
class RunnerTest extends TestCase
{
	public function test_bind_interface_to_pre_instantiated_object(): void
	{
		$runner = (new FakeRunner())->withBinds(
			new Bind(FakeContract::class, new FakeConcreteWithoutDependencies())
		);

		$resolved = $runner->resolve(FakeContract::class);

		$this->assertInstanceOf(FakeConcreteWithoutDependencies::class, $resolved);
	}

	public function test_bind_interface_to_concrete_class_without_dependencies(): void
	{
		$runner = (new FakeRunner())->withBinds(
			new Bind(FakeContract::class, FakeConcreteWithoutDependencies::class)
		);

		$resolved = $runner->resolve(FakeContract::class);

		$this->assertInstanceOf(FakeConcreteWithoutDependencies::class, $resolved);
	}

	public function test_bind_interface_to_concrete_class_with_constructor_properties_defined_in_bind(): void
	{
		$runner = (new FakeRunner())->withBinds(
			new Bind('runner-test.value', 'bound-value'),
			(new Bind(FakeContract::class, FakeConcreteWithDependency::class))
				->withProperties('runner-test.value')
		);

		$resolved = $runner->resolve(FakeContract::class);

		$this->assertInstanceOf(FakeConcreteWithDependency::class, $resolved);
		$this->assertSame('bound-value', $resolved->value);
	}

	public function test_resolves_expected_type_for_each_bind_scenario(): void
	{
		$instance = new FakeConcreteWithoutDependencies();

		$runner = (new FakeRunner())->withBinds(
			new Bind('runner-test.value', 'value-from-bind'),
			new Bind(FakePreinstantiatedContract::class, $instance),
			new Bind(FakeNoDependencyContract::class, FakeConcreteWithoutDependencies::class),
			(new Bind(FakeWithDependencyContract::class, FakeConcreteWithDependency::class))
				->withProperties('runner-test.value')
		);

		$preInstantiated = $runner->resolve(FakePreinstantiatedContract::class);
		$noDependency = $runner->resolve(FakeNoDependencyContract::class);
		$withDependency = $runner->resolve(FakeWithDependencyContract::class);

		$this->assertSame($instance, $preInstantiated);
		$this->assertInstanceOf(FakeConcreteWithoutDependencies::class, $noDependency);
		$this->assertInstanceOf(FakeConcreteWithDependency::class, $withDependency);
		$this->assertSame('value-from-bind', $withDependency->value);
	}

	public function test_invalid_bind_throws_exception_on_resolution(): void
	{
		$runner = (new FakeRunner())->withBinds(
			new Bind(FakeContract::class, 'Not\\A\\Valid\\Class')
		);

		$this->expectException(\Throwable::class);
		$runner->resolve(FakeContract::class);
	}
}

final class FakeRunner extends Runner
{
	public function execute(): void
	{
	}

	public function resolve(string $id): mixed
	{
		return $this->container->get($id);
	}
}

interface FakeContract {}
interface FakePreinstantiatedContract {}
interface FakeNoDependencyContract {}
interface FakeWithDependencyContract {}

final class FakeConcreteWithoutDependencies implements FakeContract, FakePreinstantiatedContract, FakeNoDependencyContract
{
}

final class FakeConcreteWithDependency implements FakeContract, FakeWithDependencyContract
{
	public function __construct(public readonly string $value)
	{
	}
}
