<?php

declare(strict_types=1);

namespace Webstract\Storage\Object\Image;

use Webstract\Storage\Object\FileObject;
use Webstract\Storage\Object\Prefixes;
use Psr\Http\Message\StreamInterface;

final class ComposableImage extends FileObject
{
	public function __construct(
		private string $filename,
	) {}

	protected function getPrefix(): string
	{
		return Prefixes::IMAGES->value;
	}

	protected function getKey(): string
	{
		return $this->filename;
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
		return true;
	}
}
