<?php

declare(strict_types=1);

namespace Test\Support\RoneiKunkel\Webstract\Route;

use RoneiKunkel\Webstract\Request\RequestMethod;
use RoneiKunkel\Webstract\Route\Route;
use Test\Support\RoneiKunkel\Webstract\Controller\FakeApiController;

final class FakeRoute extends Route
{
	public function getMethod(): RequestMethod
	{
		return RequestMethod::GET;
	}

	public function getPattern(): string
	{
		// @todo can be used when we implemented controller input
		// return '@/fake/(?<fake>\d+)/opa/(?<opa>\d+)/?@';
		return '@/fake/\d+/opa/\d+/?@';
	}

	public function getPathFormat(): string
	{
		return '/fake/%s/opa/%s';
	}

	public function getController(): string
	{
		return FakeApiController::class;
	}
}
