<?php

declare(strict_types=1);

namespace Webstract\Storage\Object\Zip;

use Webstract\Storage\Object\FileObject;
use Webstract\Storage\Object\Prefixes;
use Psr\Http\Message\StreamInterface;

final class RetrievedStandardInfrequentAccessGzip extends FileObject
{
	public function __construct(
		private readonly string $filepath,
	) {}

	protected function getPrefix(): string
	{
		return Prefixes::BACKUPS->value;
	}

	protected function getKey(): string
	{
		return basename($this->filepath);
	}

	public function saveAs(): string
	{
		return $this->filepath;
	}

	public function getBody(): string|StreamInterface
	{
		return '';
	}

	public function getStorageClass(): string
	{
		return '';
	}

	public function getContentType(): string
	{
		return '';
	}

	public function shouldDisposedInline(): bool
	{
		return false;
	}
}
