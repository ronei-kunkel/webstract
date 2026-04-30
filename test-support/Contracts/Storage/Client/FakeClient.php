<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Storage\Client;

use Aws\Result;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Webstract\Storage\Client\Client;
use Webstract\Storage\Object\FileObject;

class FakeClient implements Client
{
	public bool $shouldThrowOnUpload = false;

	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly FileStorageEnvironmentVarVisitor $fsEnv,
		private readonly LoggerInterface $log,
	) {}

	public function listObjects(string $prefix): Result { return new Result(['prefix' => $prefix]); }
	public function upload(FileObject $object): Result
	{
		if ($this->shouldThrowOnUpload) throw new RuntimeException('upload failed');
		return new Result(['object' => $object->getObjectName()]);
	}
	public function composeImageUri(FileObject $object): UriInterface { return new Uri('https://example.com/' . $object->getObjectName()); }
	public function download(FileObject $object): ResponseInterface { return new Response(200, body: $object->getObjectName()); }
}
