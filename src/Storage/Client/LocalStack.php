<?php

declare(strict_types=1);

namespace Webstract\Storage\Client;

use Webstract\Storage\Object\FileObject;
use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\S3\S3Client;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

final class LocalStack implements Client
{
	private string $environmentPrefix;
	private readonly S3Client $s3;
	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly FileStorageEnvironmentVarVisitor $fsEnv,
		private readonly LoggerInterface $logger,
	) {
		$this->environmentPrefix = $this->appEnv->isDevEnv() ? 'sandbox' : 'prod';

		$this->s3 = new S3Client([
			'credentials' => new Credentials(
				$this->fsEnv->getFileStoragePublicApiKey(),
				$this->fsEnv->getFileStorageSecretApiKey()
			),
			'version' => 'latest',
			'region' => $this->fsEnv->getFileStorageBucketRegion(),
			'bucket_endpoint' => true,
			'use_path_style_endpoint' => true,
			'endpoint' => "http://localstack:4566/{$this->fsEnv->getFileStorageBucketName()}",
		]);
	}

	public function listObjects(string $prefix): Result
	{
		$args = [
			'Bucket' => $this->fsEnv->getFileStorageBucketName(),
			'Delimiter' => '/',
			'Prefix' => $prefix,
		];
		$result = $this->s3->listObjectsV2($args);
		$this->logger->info('List all Objects', ['args' => $args, 'result' => $result->toArray()]);
		return $result;
	}

	public function upload(FileObject $object): Result
	{
		$args = [
			'Body' => $object->getBody(),
			'Bucket' => $this->fsEnv->getFileStorageBucketName(),
			'Key' => "{$this->environmentPrefix}/{$object->getObjectName()}",
			'StorageClass' => $object->getStorageClass(),
			'ContentType' => $object->getContentType(),
			...$object->shouldDisposedInline() ? ['ContentDisposition' => 'inline'] : []
		];
		$result = $this->s3->putObject($args);

		$this->logger->info('Object uploaded', ['args' => $args, 'result' => $result->toArray()]);

		return $result;
	}

	public function download(FileObject $object): ResponseInterface
	{
		$uri = new Uri(sprintf(
			'http://localhost:4566/%s/%s/%s',
			$this->fsEnv->getFileStorageBucketName(),
			$this->environmentPrefix,
			$object->getObjectName()
		));

		return new HttpClient()->get($uri);
	}

	public function composeImageUri(FileObject $object): UriInterface
	{
		return new Uri(sprintf(
			'http://localhost:4566/%s/%s/%s',
			$this->fsEnv->getFileStorageBucketName(),
			$this->environmentPrefix,
			$object->getObjectName()
		));
	}
}
