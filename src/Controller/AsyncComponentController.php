<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Common\HttpContentType;
use Webstract\Route\RoutePathTemplate;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;
use Webstract\Web\AsyncComponent;

abstract class AsyncComponentController extends Controller
{
	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
		protected readonly SessionHandler $session,
		protected readonly TemplateEngineRenderer $templateEngine,
	) {}

	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->response
			->withHeader('hx-redirect', $route->renderPath())
			->withStatus(303);
	}

	protected function createEmptyResponse(): ResponseInterface
	{
		return $this->response
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value . '; charset=utf-8')
			->withBody($this->stream)
			->withStatus(200);
	}

	protected function createHtmlResponse(AsyncComponent $component): ResponseInterface
	{
		$component = $component->shouldRendered()
			? $this->templateEngine->render($component->htmlPath(), $component->getContext())
			: file_get_contents($component->htmlPath());

		$this->stream->write($component);
		return $this->response
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value . '; charset=utf-8')
			->withBody($this->stream)
			->withStatus(200);
	}
}
