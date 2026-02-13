<?php

declare(strict_types=1);

namespace Webstract\Storage\Object\Zip;

use Webstract\Storage\Object\FileObject;
use Webstract\Storage\Object\Prefixes;
use Psr\Http\Message\StreamInterface;

final class StandardInfrequentAccessGzip extends FileObject
{
	private string $filename;

	public function __construct(
		private readonly StreamInterface $stream,
	) {
		$this->filename = $stream->getMetadata('uri');
	}

	protected function getPrefix(): string
	{
		return Prefixes::BACKUPS->value;
	}

	protected function getKey(): string
	{
		return basename($this->filename);
	}

	public function getBody(): string|StreamInterface
	{
		return $this->stream;
	}

	public function getStorageClass(): string
	{
		return 'STANDARD_IA';
	}

	public function getContentType(): string
	{
		return mime_content_type($this->filename);
	}

	public function shouldDisposedInline(): bool
	{
		return false;
	}
}
