<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Pdf\PdfGenerator;
use Webstract\Session\SessionHandler;

abstract class ActionController extends Controller
{
	use DownloadableResponse;
	use SimpleRedirectableResponse;

	public function __construct(
		protected readonly ResponseInterface $response,
		protected readonly StreamInterface $stream,
		protected readonly SessionHandler $session,
		protected readonly PdfGenerator $pdfGenerator,
	) {}
}
