<?php

declare(strict_types=1);

namespace Webstract\Storage\Object;

enum Prefixes: string
{
	case IMAGES = 'images';
	case BACKUPS = 'backups';
}
