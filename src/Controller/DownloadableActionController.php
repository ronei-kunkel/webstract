<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Controller\Traits\DownloadableResponse;
use Webstract\Pdf\PdfGenerator;
use Webstract\Session\SessionHandler;

abstract class DownloadableActionController extends ActionController
{
	use DownloadableResponse;

	public function __construct(
		ResponseInterface $response,
		StreamInterface $stream,
		SessionHandler $session,
		protected readonly PdfGenerator $pdfGenerator,
	) {
		parent::__construct(
			$response,
			$stream,
			$session,
		);
	}
}
