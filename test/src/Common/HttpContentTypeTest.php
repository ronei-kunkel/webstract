<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Common\HttpContentType;

it('returns the correct ContentType for valid extensions', function (string $fileName, HttpContentType $expectedContentType) {
	$result = HttpContentType::fromFilename($fileName);
	expect($result)->toBe($expectedContentType);
})->with([
	['file.txt', HttpContentType::TXT],
	['document.html', HttpContentType::HTML],
	['image.JPG', HttpContentType::JPG],
	['audio.mp3', HttpContentType::MP3],
	['video.mp4', HttpContentType::MP4],
	['archive.zip', HttpContentType::ZIP],
	['spreadsheet.xlsx', HttpContentType::XLSX],
	['font.woff2', HttpContentType::WOFF2],
	['script.js', HttpContentType::JS],
	['unknown-case.PHP', HttpContentType::PHP],
]);

it('returns null for unknown or unsupported extensions', function (string $fileName) {
	$result = HttpContentType::fromFilename($fileName);
	expect($result)->toBeNull();
})->with([
	'unknown.filetype',
	'noextension',
	'image.jpeg2000',
	'.hiddenfile',
	'unknowncase.INVALID',
]);

it('has all the expected cases', function (array $expectedCases) {
	expect(HttpContentType::cases())->toMatchArray($expectedCases);
})->with([
	[
		[
			HttpContentType::TXT,
			HttpContentType::HTML,
			HttpContentType::CSS,
			HttpContentType::CSV,
			HttpContentType::JPG,
			HttpContentType::PNG,
			HttpContentType::GIF,
			HttpContentType::SVG,
			HttpContentType::WEBP,
			HttpContentType::BMP,
			HttpContentType::TIFF,
			HttpContentType::MP3,
			HttpContentType::OGG_AUDIO,
			HttpContentType::WAV,
			HttpContentType::AAC,
			HttpContentType::FLAC,
			HttpContentType::MP4,
			HttpContentType::MPEG,
			HttpContentType::OGG_VIDEO,
			HttpContentType::WEBM,
			HttpContentType::AVI,
			HttpContentType::PHP,
			HttpContentType::JS,
			HttpContentType::XML,
			HttpContentType::JSON,
			HttpContentType::PDF,
			HttpContentType::ZIP,
			HttpContentType::GZIP,
			HttpContentType::DOC,
			HttpContentType::XLS,
			HttpContentType::XLSX,
			HttpContentType::DOCX,
			HttpContentType::RAR,
			HttpContentType::TAR,
			HttpContentType::OCTET_STREAM,
			HttpContentType::TTF,
			HttpContentType::WOFF,
			HttpContentType::WOFF2,
			HttpContentType::FORM_DATA,
			HttpContentType::MULTIPART_MIXED,
		]
	]
]);

it('cases have expected values', function (HttpContentType $case, string $expectedValue) {
	expect($case->value)->toBe($expectedValue);
})->with([
	[HttpContentType::TXT, 'text/plain'],
	[HttpContentType::HTML, 'text/html'],
	[HttpContentType::CSS, 'text/css'],
	[HttpContentType::CSV, 'text/csv'],
	[HttpContentType::JPG, 'image/jpeg'],
	[HttpContentType::PNG, 'image/png'],
	[HttpContentType::GIF, 'image/gif'],
	[HttpContentType::SVG, 'image/svg+xml'],
	[HttpContentType::WEBP, 'image/webp'],
	[HttpContentType::BMP, 'image/bmp'],
	[HttpContentType::TIFF, 'image/tiff'],
	[HttpContentType::MP3, 'audio/mpeg'],
	[HttpContentType::OGG_AUDIO, 'audio/ogg'],
	[HttpContentType::WAV, 'audio/wav'],
	[HttpContentType::AAC, 'audio/aac'],
	[HttpContentType::FLAC, 'audio/flac'],
	[HttpContentType::MP4, 'video/mp4'],
	[HttpContentType::MPEG, 'video/mpeg'],
	[HttpContentType::OGG_VIDEO, 'video/ogg'],
	[HttpContentType::WEBM, 'video/webm'],
	[HttpContentType::AVI, 'video/x-msvideo'],
	[HttpContentType::PHP, 'application/x-httpd-php'],
	[HttpContentType::JS, 'application/javascript'],
	[HttpContentType::XML, 'application/xml'],
	[HttpContentType::JSON, 'application/json'],
	[HttpContentType::PDF, 'application/pdf'],
	[HttpContentType::ZIP, 'application/zip'],
	[HttpContentType::GZIP, 'application/gzip'],
	[HttpContentType::DOC, 'application/msword'],
	[HttpContentType::XLS, 'application/vnd.ms-excel'],
	[HttpContentType::XLSX, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
	[HttpContentType::DOCX, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
	[HttpContentType::RAR, 'application/x-rar-compressed'],
	[HttpContentType::TAR, 'application/x-tar'],
	[HttpContentType::OCTET_STREAM, 'application/octet-stream'],
	[HttpContentType::TTF, 'font/ttf'],
	[HttpContentType::WOFF, 'font/woff'],
	[HttpContentType::WOFF2, 'font/woff2'],
	[HttpContentType::FORM_DATA, 'multipart/form-data'],
	[HttpContentType::MULTIPART_MIXED, 'multipart/mixed'],
]);
