<?php

declare(strict_types=1);

namespace Webstract\Storage\Object;

use Psr\Http\Message\StreamInterface;

abstract class FileObject
{
	abstract protected function getPrefix(): string;
	abstract protected function getKey(): string;
	abstract public function getBody(): string|StreamInterface;
	abstract public function getStorageClass(): string;
	abstract public function getContentType(): string;
	abstract public function shouldDisposedInline(): bool;

	public function saveAs(): string
	{
		return '';
	}

	public function getObjectName(): string
	{
		return "{$this->getPrefix()}/{$this->getKey()}";
	}
}
