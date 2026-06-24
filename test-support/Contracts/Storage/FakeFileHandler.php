<?php

declare(strict_types=1);

namespace Test\Support\Contracts\Storage;

use Nyholm\Psr7\Uri;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Webstract\Storage\FileHandler;
use Webstract\Storage\Path;

class FakeFileHandler implements FileHandler
{
	public bool $shouldThrowOnUpload = false;
	public function __construct(
		private readonly ApplicationEnvironmentVarVisitor $appEnv,
		private readonly FileStorageEnvironmentVarVisitor $fsEnv,
		private readonly LoggerInterface $logger,
	) {}
	public function listObjectsFromPath(Path $path): array { return [$path->value]; }
	public function uploadInlineImage(UploadedFileInterface $file): UriInterface
	{
		if ($this->shouldThrowOnUpload) throw new RuntimeException('inline upload failed');
		return new Uri('https://example.com/image.png');
	}
	public function resolveImageUri(string $image): UriInterface { return new Uri('https://example.com/' . ltrim($image, '/')); }
	public function uploadDatabaseBackup(StreamInterface $stream): void {}
	public function downloadDatabaseBackup(string $filepath): void
	{
		if ($filepath === '') throw new RuntimeException('filepath required');
	}
}
