<?php

declare(strict_types=1);

namespace Webstract\Storage\Object\Image;

use Webstract\Storage\Object\FileObject;
use Webstract\Storage\Object\Prefixes;
use DateTime;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

final class InlineStandardImage extends FileObject
{
	private string $filename;

	public function __construct(
		private UploadedFileInterface $file,
		private ?string $referrer = null,
	) {
		$timestamp = (string) (new DateTime())->getTimestamp();
		$name = str_replace('.', '', uniqid($timestamp . '_', true));
		$extension = pathinfo($this->file->getClientFilename(), PATHINFO_EXTENSION);

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
		return $this->file->getStream();
	}

	public function getStorageClass(): string
	{
		return 'STANDARD';
	}

	public function getContentType(): string
	{
		return $this->file->getClientMediaType();
	}

	public function shouldDisposedInline(): bool
	{
		return true;
	}
}
