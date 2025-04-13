<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RoneiKunkel\Webstract\Common\HttpContentType;
use RoneiKunkel\Webstract\Page\PageTemplate;
use RoneiKunkel\Webstract\Session\SessionHandlerInterface;
use RoneiKunkel\Webstract\TemplateEngine\TemplateEngineRenderer;

abstract class PageController extends ActionController
{
	public function __construct(
		ResponseInterface $responseInterface,
		StreamInterface $streamInterface,
		SessionHandlerInterface $sessionHandlerInterface,
		private readonly TemplateEngineRenderer $templateEngineRenderer,
	) {
		parent::__construct($responseInterface, $streamInterface, $sessionHandlerInterface);
	}

	protected function createHtmlResponse(PageTemplate $template): ResponseInterface
	{
		$this->streamInterface->write(
			$this->templateEngineRenderer->render($template->htmlPath(), $template->getContext())
		);

		return $this->responseInterface
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value)
			->withBody($this->streamInterface)
			->withStatus(200);
	}
}
