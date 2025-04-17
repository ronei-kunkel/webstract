<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Common\HttpContentType;
use Webstract\Web\Page;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

abstract class PageController extends ActionController
{
	public function __construct(
		ResponseInterface $responseInterface,
		StreamInterface $streamInterface,
		SessionHandler $sessionHandlerInterface,
		private readonly TemplateEngineRenderer $templateEngineRenderer,
	) {
		parent::__construct($responseInterface, $streamInterface, $sessionHandlerInterface);
	}

	protected function createHtmlResponse(Page $page): ResponseInterface
	{
		$this->streamInterface->write(
			$this->templateEngineRenderer->render($page->htmlPath(), $page->getContext())
		);

		return $this->responseInterface
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value)
			->withBody($this->streamInterface)
			->withStatus(200);
	}
}
