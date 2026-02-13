<?php

declare(strict_types=1);

namespace Webstract\Storage;

use Webstract\Env\Visitor\ApplicationEnvironmentVarVisitor;
use Webstract\Env\Visitor\FileStorageEnvironmentVarVisitor;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

interface FileHandler
{
	public function __construct(
		ApplicationEnvironmentVarVisitor $appEnv,
		FileStorageEnvironmentVarVisitor $fsEnv,
		LoggerInterface $logger,
	);

	public function listObjectsFromPath(Path $path): array;

	public function uploadInlineImage(UploadedFileInterface $file): UriInterface;
	public function resolveImageUri(string $image): UriInterface;

	public function uploadDatabaseBackup(StreamInterface $stream): void;
	public function downloadDatabaseBackup(string $filepath): void;
}
