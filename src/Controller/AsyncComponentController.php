<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Common\HttpContentType;
use RoneiKunkel\Webstract\Route\RoutePathTemplate;
use RoneiKunkel\Webstract\Session\SessionHandlerInterface;
use RoneiKunkel\Webstract\TemplateEngine\TemplateEngineRenderer;
use RoneiKunkel\Webstract\Web\AsyncComponent;

abstract class AsyncComponentController extends Controller
{
	public function __construct(
		protected readonly ResponseInterface $responseInterface,
		protected readonly StreamInterface $streamInterface,
		protected readonly SessionHandlerInterface $sessionService,
		private readonly TemplateEngineRenderer $templateEngineRenderer,
	) {}

	protected function createRedirectResponse(RoutePathTemplate $route): ResponseInterface
	{
		return $this->responseInterface
			->withHeader('hx-redirect', $route->renderPath())
			->withStatus(303);
	}

	protected function createEmptyResponse(): ResponseInterface
	{
		return $this->responseInterface
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value . '; charset=utf-8')
			->withBody($this->streamInterface)
			->withStatus(200);
	}

	protected function createHtmlResponse(AsyncComponent $component): ResponseInterface
	{
		$component = $component->shouldRendered()
			? $this->templateEngineRenderer->render($component->htmlPath(), $component->getContext())
			: file_get_contents($component->htmlPath());

		$this->streamInterface->write($component);
		return $this->responseInterface
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value . '; charset=utf-8')
			->withBody($this->streamInterface)
			->withStatus(200);
	}
}
