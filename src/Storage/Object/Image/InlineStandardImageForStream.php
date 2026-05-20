<?php

declare(strict_types=1);

namespace Webstract\Storage\Object\Image;

use Webstract\Storage\Object\FileObject;
use Webstract\Storage\Object\Prefixes;
use DateTime;
use Psr\Http\Message\StreamInterface;

final class InlineStandardImageForStream extends FileObject
{
	private string $filename;

	public function __construct(
		private StreamInterface $stream,
		private string $name,
		private string $extension,
	) {
		$this->filename = sprintf(
			'%s.%s',
			$name,
			$extension
		);
	}

	protected function getPrefix(): string
	{
		return Prefixes::IMAGES->value;
	}

	protected function getKey(): string
	{
		return $this->filename;
	}

	public function getBody(): StreamInterface
	{
		return $this->stream;
	}

	public function getStorageClass(): string
	{
		return 'STANDARD';
	}

	public function getContentType(): string
	{
		return pathinfo($this->filename, PATHINFO_EXTENSION);
	}

	public function shouldDisposedInline(): bool
	{
		return true;
	}
}
