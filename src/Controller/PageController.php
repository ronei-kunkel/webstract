<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Common\HttpContentType;
use Webstract\Web\Page;
use Webstract\Session\SessionHandler;
use Webstract\TemplateEngine\TemplateEngineRenderer;

abstract class PageController extends Controller
{
	use SimpleRedirectableResponse;

	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
		protected readonly SessionHandler $session,
		protected readonly TemplateEngineRenderer $templateEngine,
	) {}

	protected function createHtmlResponse(Page $page): ResponseInterface
	{
		$this->stream->write(
			$this->templateEngine->render($page->htmlPath(), $page->getContext())
		);

		return $this->response
			->withHeader(HttpContentType::getHeaderName(), HttpContentType::HTML->value)
			->withBody($this->stream)
			->withStatus(200);
	}
}
