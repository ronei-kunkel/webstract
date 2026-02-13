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
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

final class Oracle implements Client
{
	private const string DEV_PREAUTH_READ_IMAGES_KEY = 'Xzqk7CH5Ela0Y9Mn_vXGGoWTdRRWiWFbme9tTquSTaOzYEUUW7wpfJPTbFmLlAyi';
	private const string PROD_PREAUTH_READ_IMAGES_KEY = '9a67mffSFlEB-YZOvjVlQTKnE7Al2I_TivUVztfgSxwLQT3Hv58xY9yK3Xkwz1bu';

	private const string DEV_FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY = 'EmZ4x1PIIM7Fy3nYT4ZGWJaNjFtT3G1Q_9JedDV3fq0Sjgx0vFyLGDxuZT8hiv0c';
	private const string PROD_FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY = '1PX_7HQ0zVkCUOdJXHYsGROrWOTkr9L0OBnM2Jn8f57jeX4tcmRrPXPgvrj-8u6I';

	private string $bucketName;
	private string $bucketNamespace;
	private string $region;
	private string $environmentPrefix;

	private readonly S3Client $s3;

	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly FileStorageEnvironmentVarVisitor $fsEnv,
		private readonly LoggerInterface $logger,
	) {
		$this->region = $this->fsEnv->getFileStorageBucketRegion();
		$this->bucketName = $this->fsEnv->getFileStorageBucketName();
		$this->bucketNamespace = $this->fsEnv->getFileStorageBucketNamespace();
		$this->environmentPrefix = $this->appEnv->isDevEnv() ? 'sandbox' : 'prod';
		$this->s3 = new S3Client([
			'credentials' => new Credentials(
				$this->fsEnv->getFileStoragePublicApiKey(),
				$this->fsEnv->getFileStorageSecretApiKey()
			),
			'version' => 'latest',
			'region' => $this->region,
			'endpoint' => "https://{$this->bucketNamespace}.compat.objectstorage.{$this->region}.oci.customer-oci.com",
			'use_path_style_endpoint' => true,
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
			'Bucket' => $this->bucketName,
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
			'https://%s.objectstorage.%s.oci.customer-oci.com/p/%s/n/%s/b/%s/o/%s',
			$this->bucketNamespace,
			$this->region,
			$this->appEnv->isDevEnv() ? self::DEV_FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY : self::PROD_FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY,
			$this->bucketNamespace,
			$this->bucketName,
			"{$this->environmentPrefix}/{$object->getObjectName()}",
		));

		return new HttpClient()->get($uri);
	}

	public function composeImageUri(FileObject $object): UriInterface
	{
		return new Uri(sprintf(
			'https://%s.objectstorage.%s.oci.customer-oci.com/p/%s/n/%s/b/%s/o/%s',
			$this->bucketNamespace,
			$this->region,
			$this->appEnv->isDevEnv() ? self::DEV_PREAUTH_READ_IMAGES_KEY : self::PROD_PREAUTH_READ_IMAGES_KEY,
			$this->bucketNamespace,
			$this->bucketName,
			"{$this->environmentPrefix}/{$object->getObjectName()}",
		));
	}
}
