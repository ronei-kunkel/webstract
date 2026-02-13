<?php

declare(strict_types=1);

namespace Webstract\Storage;

enum Path: string
{
	case ROOT = '';

	case BACKUPS = 'backups/'; // @todo to be removed
	case IMAGES = 'images/'; // @todo to be removed

	case PROD = 'prod/';
	case PROD_BACKUPS = 'prod/backups/';
	case PROD_IMAGES = 'prod/images/';

	case SANDBOX = 'sandbox/';
	case SANDBOX_BACKUPS = 'sandbox/backups/';
	case SANDBOX_IMAGES = 'sandbox/images/';

	public function getPrevious(): ?self
	{
		return match ($this) {
			self::ROOT => null,

			self::BACKUPS => self::ROOT,
			self::IMAGES => self::ROOT,
			
			self::PROD => self::ROOT,
			self::PROD_BACKUPS => self::PROD,
			self::PROD_IMAGES => self::PROD,
			
			self::SANDBOX => self::ROOT,
			self::SANDBOX_BACKUPS => self::SANDBOX,
			self::SANDBOX_IMAGES => self::SANDBOX,
		};
	}
}
