<?php

declare(strict_types=1);

namespace Webstract\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webstract\Controller\Traits\DownloadableResponse;
use Webstract\Pdf\PdfGenerator;

abstract class DownloadableApiController extends ApiController
{
	use DownloadableResponse;

	public function __construct(
		ResponseInterface $response,
		StreamInterface $stream,
		protected readonly PdfGenerator $pdfGenerator,
	) {
		parent::__construct(
			$response,
			$stream,
		);
	}
}
