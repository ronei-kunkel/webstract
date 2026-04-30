<?php

declare(strict_types=1);

namespace Test\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use Test\TestCase;

class AbstractionsContractsTest extends TestCase
{
	public static function provideInterfaces(): iterable
	{
		yield [\Webstract\Database\DatabaseTransactionManager::class];
		yield [\Webstract\Database\DatabaseRepositoryConnector::class];
		yield [\Webstract\Database\Repository::class];
		yield [\Webstract\TemplateEngine\TemplateEngineRenderer::class];
		yield [\Webstract\Controller\Controller::class];
		yield [\Webstract\Env\EnvironmentVarInterface::class];
		yield [\Webstract\Env\EnvironmentVarLoaderInterface::class];
		yield [\Webstract\Env\EnvironmentHandlerInterface::class];
		yield [\Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor::class];
		yield [\Webstract\Env\Visitor\LogEnvironmentVarVisitor::class];
		yield [\Webstract\Env\Visitor\DatabaseEnvironmentVarVisitor::class];
		yield [\Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor::class];
		yield [\Webstract\Log\Collector\LogCollector::class];
		yield [\Webstract\Route\RouteHandleable::class];
		yield [\Webstract\Route\RouteResolver::class];
		yield [\Webstract\Route\RouteDefinition::class];
		yield [\Webstract\Route\RouteProviderInterface::class];
		yield [\Webstract\Session\SessionHandler::class];
		yield [\Webstract\Session\SessionKeyInterface::class];
		yield [\Webstract\Session\KeyValueSessionHandler::class];
		yield [\Webstract\Session\Visitors\UserSessionVisitor::class];
		yield [\Webstract\Storage\FileHandler::class];
		yield [\Webstract\Storage\Client\Client::class];
		yield [\Webstract\Rbac\PermissionProvider::class];
		yield [\Webstract\Cli\CommandProviderInterface::class];
		yield [\Webstract\Pdf\PdfGenerator::class];
	}

	#[DataProvider('provideInterfaces')]
	public function test_ShouldKeepInterfaceContracts(string $fqcn): void
	{
		$this->assertTrue(interface_exists($fqcn), "Expected interface {$fqcn} to exist");
		$this->assertTrue((new ReflectionClass($fqcn))->isInterface());
	}

	public static function provideAbstractClasses(): iterable
	{
		yield [\Webstract\Controller\ActionController::class];
		yield [\Webstract\Controller\ApiController::class];
		yield [\Webstract\Controller\PageController::class];
		yield [\Webstract\Controller\AsyncComponentController::class];
		yield [\Webstract\Controller\DownloadableApiController::class];
		yield [\Webstract\Controller\DownloadableActionController::class];
		yield [\Webstract\Route\RouterOutputProvider::class];
		yield [\Webstract\Route\RoutePathTemplate::class];
		yield [\Webstract\Storage\Object\FileObject::class];
		yield [\Webstract\Runner\Runner::class];
		yield [\Webstract\Request\SafeRequestHandlerServerErrorControllerProvider::class];
		yield [\Webstract\Cli\Command::class];
		yield [\Webstract\Pdf\PdfContent::class];
		yield [\Webstract\Web\Content::class];
		yield [\Webstract\Web\Component::class];
		yield [\Webstract\Web\AsyncComponent::class];
		yield [\Webstract\Web\Page::class];
	}

	#[DataProvider('provideAbstractClasses')]
	public function test_ShouldKeepAbstractBaseClasses(string $fqcn): void
	{
		$this->assertTrue(class_exists($fqcn), "Expected class {$fqcn} to exist");
		$this->assertTrue((new ReflectionClass($fqcn))->isAbstract());
	}

	public function test_ControllerContractShouldExtendRequestHandlerInterface(): void
	{
		$interfaces = class_implements(\Webstract\Controller\Controller::class);
		$this->assertContains(\Psr\Http\Server\RequestHandlerInterface::class, $interfaces);
	}

	public function test_RouteDefinitionShouldExtendRouteHandleable(): void
	{
		$interfaces = class_implements(\Webstract\Route\RouteDefinition::class);
		$this->assertContains(\Webstract\Route\RouteHandleable::class, $interfaces);
	}

	public function test_WebHierarchyShouldBePreserved(): void
	{
		$this->assertSame(\Webstract\Web\Component::class, get_parent_class(\Webstract\Web\AsyncComponent::class));
		$reflection = new ReflectionClass(\Webstract\Web\Page::class);
		$property = $reflection->getProperty('content');
		$this->assertSame('content', $property->getName());
		$this->assertSame(\Webstract\Web\Content::class, $property->getType()?->getName());
	}
}
