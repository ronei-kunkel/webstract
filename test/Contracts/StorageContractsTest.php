<?php

declare(strict_types=1);

namespace Test\Contracts;

use Nyholm\Psr7\UploadedFile;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\NullLogger;
use RuntimeException;
use Test\Support\Contracts\Storage\Client\FakeClient;
use Test\Support\Contracts\Storage\FakeFileHandler;
use Test\TestCase;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Webstract\Storage\Path;

class StorageContractsTest extends TestCase
{
	public function test_ShouldReturnListedObjects_WhenPathIsProvided(): void
	{
		$handler = new FakeFileHandler($this->createStub(ApplicationEnvironmentVarVisitor::class), $this->createStub(FileStorageEnvironmentVarVisitor::class), new NullLogger());
		$this->assertSame([Path::PROD_IMAGES->value], $handler->listObjectsFromPath(Path::PROD_IMAGES));
	}

	public function test_ShouldThrowException_WhenInlineUploadFails(): void
	{
		$factory = new Psr17Factory();
		$handler = new FakeFileHandler($this->createStub(ApplicationEnvironmentVarVisitor::class), $this->createStub(FileStorageEnvironmentVarVisitor::class), new NullLogger());
		$handler->shouldThrowOnUpload = true;
		$this->expectException(RuntimeException::class);
		$handler->uploadInlineImage(new UploadedFile($factory->createStream('x'), 1, UPLOAD_ERR_OK, 'img.png', 'image/png'));
	}

	public function test_ShouldReturnResultAndUri_WhenClientOperationsSucceed(): void
	{
		$client = new FakeClient($this->createStub(ApplicationEnvironmentVarVisitor::class), $this->createStub(FileStorageEnvironmentVarVisitor::class), new NullLogger());
		$result = $client->listObjects('prod/images/');
		$this->assertSame('prod/images/', $result['prefix']);
	}
}
