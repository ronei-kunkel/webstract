<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Request;

enum RequestMethod
{
	case GET;
	case POST;
	case PUT;
	case DELETE;
	case PATCH;
	case HEAD;
	case OPTIONS;
	case CONNECT;
	case TRACE;

	// public static function tryResolve(string $method): ?self
	// {
	// 	foreach (self::cases() as $case) {
	// 		if ($case->name === $method) {
	// 			return $case;
	// 		}
	// 	}

	// 	return null;
	// }

	// public function __toString(): string
	// {
	// 	return $this->name;
	// }
}
