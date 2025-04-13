<?php

declare(strict_types=1);

namespace RoneiKunkel\Webstract\Common;

enum HttpContentType: string
{
	case TXT = 'text/plain';
	case HTML = 'text/html';
	case CSS = 'text/css';
	case CSV = 'text/csv';

	case JPG = 'image/jpeg';
	case PNG = 'image/png';
	case GIF = 'image/gif';
	case SVG = 'image/svg+xml';
	case WEBP = 'image/webp';
	case BMP = 'image/bmp';
	case TIFF = 'image/tiff';

	case MP3 = 'audio/mpeg';
	case OGG_AUDIO = 'audio/ogg';
	case WAV = 'audio/wav';
	case AAC = 'audio/aac';
	case FLAC = 'audio/flac';

	case MP4 = 'video/mp4';
	case MPEG = 'video/mpeg';
	case OGG_VIDEO = 'video/ogg';
	case WEBM = 'video/webm';
	case AVI = 'video/x-msvideo';

	case PHP = 'application/x-httpd-php';
	case JS = 'application/javascript';
	case XML = 'application/xml';
	case JSON = 'application/json';
	case PDF = 'application/pdf';
	case ZIP = 'application/zip';
	case GZIP = 'application/gzip';
	case DOC = 'application/msword';
	case XLS = 'application/vnd.ms-excel';
	case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	case RAR = 'application/x-rar-compressed';
	case TAR = 'application/x-tar';
	case OCTET_STREAM = 'application/octet-stream';

	case TTF = 'font/ttf';
	case WOFF = 'font/woff';
	case WOFF2 = 'font/woff2';

	case FORM_DATA = 'multipart/form-data';
	case MULTIPART_MIXED = 'multipart/mixed';

	public static function fromFilename(string $fileName): ?self
	{
		$extension = pathinfo($fileName, PATHINFO_EXTENSION);

		return match (strtolower($extension)) {
			'txt' => self::TXT,
			'html',
			'htm' => self::HTML,
			'css' => self::CSS,
			'js' => self::JS,
			'php' => self::PHP,
			'xml' => self::XML,
			'json' => self::JSON,
			'csv' => self::CSV,
			'jpg',
			'jpeg' => self::JPG,
			'png' => self::PNG,
			'gif' => self::GIF,
			'svg' => self::SVG,
			'webp' => self::WEBP,
			'bmp' => self::BMP,
			'tiff',
			'tif' => self::TIFF,
			'mp3' => self::MP3,
			'ogg' => self::OGG_AUDIO,
			'wav' => self::WAV,
			'aac' => self::AAC,
			'flac' => self::FLAC,
			'mp4' => self::MP4,
			'mpeg',
			'mpg' => self::MPEG,
			'ogv' => self::OGG_VIDEO,
			'webm' => self::WEBM,
			'avi' => self::AVI,
			'pdf' => self::PDF,
			'zip' => self::ZIP,
			'gz',
			'gzip' => self::GZIP,
			'doc' => self::DOC,
			'xls' => self::XLS,
			'xlsx' => self::XLSX,
			'docx' => self::DOCX,
			'rar' => self::RAR,
			'tar' => self::TAR,
			'ttf' => self::TTF,
			'woff' => self::WOFF,
			'woff2' => self::WOFF2,
			'bin',
			'exe' => self::OCTET_STREAM,
			default => null,
		};
	}

	public static function getHeaderName(): string
	{
		return 'Content-Type';
	}
}
