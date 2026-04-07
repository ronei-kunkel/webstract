<?php

declare(strict_types=1);

namespace Webstract\Env;

use Webstract\Env\EnvironmentVarInterface;

enum EnvVar: string implements EnvironmentVarInterface
{
	// case APP_NAME = 'App Name';
	case APP_NAME = 'APP_NAME';
		// case ROOT_DIR = '/app';
	case ROOT_DIR = 'ROOT_DIR';
		// case DEV_ENV_LOG_DIR = self::ROOT_DIR . '/.log/php/app.log';
	case DEV_ENV_LOG_DIR = 'DEV_ENV_LOG_DIR';
	case ENVIRONMENT = 'ENVIRONMENT';

	case DB_HOST = 'DB_HOST';
	case DB_NAME = 'DB_NAME';
	case DB_USER = 'DB_USER';
	case DB_PASS = 'DB_PASS';
	case DB_TYPE = 'DB_TYPE';
	case DB_PORT = 'DB_PORT';

	case LOG_API_KEY = 'LOG_API_KEY';
		// case LOG_BASE_URL = 'https://log-api.newrelic.com';
	case LOG_BASE_URL = 'LOG_BASE_URL';
		// case LOG_ENDPOINT = '/log/v1';
	case LOG_ENDPOINT = 'LOG_ENDPOINT';

	case FILE_STORAGE_PUBLIC_API_KEY = 'FILE_STORAGE_PUBLIC_API_KEY';
	case FILE_STORAGE_SECRET_API_KEY = 'FILE_STORAGE_SECRET_API_KEY';
	case FILE_STORAGE_BUCKET_REGION = 'FILE_STORAGE_BUCKET_REGION';
	case FILE_STORAGE_BUCKET_NAME = 'FILE_STORAGE_BUCKET_NAME';
	case FILE_STORAGE_BUCKET_NAMESPACE = 'FILE_STORAGE_BUCKET_NAMESPACE';
	case FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY = 'FILE_STORAGE_BUCKET_BACKUP_PREFIX_PRE_AUTH_KEY';

	public function getName(): string
	{
		return $this->value;
	}
}
