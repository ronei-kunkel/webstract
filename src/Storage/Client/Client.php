<?php

declare(strict_types=1);

namespace Webstract\Storage\Client;

use Webstract\Storage\Object\FileObject;
use Aws\Result;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

interface Client
{
	public function __construct(
		ApplicationEnvironmentVarVisitor $appEnv,
		FileStorageEnvironmentVarVisitor $fsEnv,
		LoggerInterface $log,
	);
	/** @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectsv2 */
	public function listObjects(string $prefix): Result;
	/** @see - https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject */
	public function upload(FileObject $object): Result;
	public function composeImageUri(FileObject $object): UriInterface;
	public function download(FileObject $object): ResponseInterface;
}
