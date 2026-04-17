<?php

declare(strict_types=1);

namespace Webstract\Database;

interface Repository
{
	public function __construct(DatabaseRepositoryConnector $connector);
}
