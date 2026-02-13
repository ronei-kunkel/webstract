<?php

declare(strict_types=1);

namespace Webstract\Storage;

use Webstract\Storage\Client\Client;
use Webstract\Storage\Client\LocalStack;
use Webstract\Storage\Client\Oracle;
use Webstract\Storage\FileHandler;
use Webstract\Storage\Object\Image\ComposableImage;
use Webstract\Storage\Object\Image\InlineStandardImage;
use Webstract\Storage\Object\Zip\RetrievedStandardInfrequentAccessGzip;
use Webstract\Storage\Object\Zip\StandardInfrequentAccessGzip;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

final class S3FileHandler implements FileHandler
{
	private Client $client;

	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly FileStorageEnvironmentVarVisitor $fsEnv,
		private readonly LoggerInterface $logger,
	) {
		$this->client = $this->appEnv->isDevEnv()
			? new LocalStack($this->appEnv, $this->fsEnv, $this->logger)
			: new Oracle($this->appEnv, $this->fsEnv, $this->logger);
	}

	public function listObjectsFromPath(Path $path): array
	{
		$result = $this->client->listObjects($path->value)->toArray();
		$folders = $result['CommonPrefixes'] ?? [];
		$files = $result['Contents'] ?? [];

		$folders = array_map(fn($item) => ['name' => $item['Prefix'], 'type' => 'folder'], $folders);

		// $item['Size'] is in BITES and will be converted to Mb
		$files = array_map(fn($item) => ['name' => $item['Key'], 'size' => $item['Size'] / 1000 / 1000, 'date' => $item['LastModified']->format('d-m-Y H:i:s'), 'type' => 'file'], $files);

		$contents = array_merge($folders, $files);

		return $contents;
	}

	public function uploadInlineImage(UploadedFileInterface $file): UriInterface
	{
		$object = new InlineStandardImage($file);
		$result = $this->client->upload($object);
		$this->logger->info('Object uploaded', $result->toArray());
		return $this->client->composeImageUri($object);
	}

	public function resolveImageUri(string $name): UriInterface
	{
		$object = new ComposableImage($name);
		return $this->client->composeImageUri($object);
	}

	public function uploadDatabaseBackup(StreamInterface $stream): void
	{
		$object = new StandardInfrequentAccessGzip($stream);
		$this->client->upload($object);
	}

	public function downloadDatabaseBackup(string $filepath): void
	{
		$object = new RetrievedStandardInfrequentAccessGzip($filepath);
		$response = $this->client->download($object);
		file_put_contents($filepath, $response->getBody());
	}
}
