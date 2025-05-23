<?php

declare(strict_types=1);

namespace Webstract\Request;

enum RequestMethod: string
{
	case GET = 'GET';
	case POST = 'POST';
	case PUT = 'PUT';
	case DELETE = 'DELETE';
	case PATCH = 'PATCH';
	case HEAD = 'HEAD';
	case OPTIONS = 'OPTIONS';
	case CONNECT = 'CONNECT';
	case TRACE = 'TRACE';
}
