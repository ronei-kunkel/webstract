<?php

declare(strict_types=1);

use RoneiKunkel\Webstract\Common\Controller\ContentType;

it('returns the correct ContentType for valid extensions', function (string $fileName, ContentType $expectedContentType) {
	$result = ContentType::fromFilename($fileName);
	expect($result)->toBe($expectedContentType);
})->with([
	['file.txt', ContentType::TXT],
	['document.html', ContentType::HTML],
	['image.JPG', ContentType::JPG],
	['audio.mp3', ContentType::MP3],
	['video.mp4', ContentType::MP4],
	['archive.zip', ContentType::ZIP],
	['spreadsheet.xlsx', ContentType::XLSX],
	['font.woff2', ContentType::WOFF2],
	['script.js', ContentType::JS],
	['unknown-case.PHP', ContentType::PHP],
]);

it('returns null for unknown or unsupported extensions', function (string $fileName) {
	$result = ContentType::fromFilename($fileName);
	expect($result)->toBeNull();
})->with([
	'unknown.filetype',
	'noextension',
	'image.jpeg2000',
	'.hiddenfile',
	'unknowncase.INVALID',
]);

it('has all the expected cases', function () {
	$expectedCases = [
		ContentType::TXT,
		ContentType::HTML,
		ContentType::CSS,
		ContentType::CSV,
		ContentType::JPG,
		ContentType::PNG,
		ContentType::GIF,
		ContentType::SVG,
		ContentType::WEBP,
		ContentType::BMP,
		ContentType::TIFF,
		ContentType::MP3,
		ContentType::OGG_AUDIO,
		ContentType::WAV,
		ContentType::AAC,
		ContentType::FLAC,
		ContentType::MP4,
		ContentType::MPEG,
		ContentType::OGG_VIDEO,
		ContentType::WEBM,
		ContentType::AVI,
		ContentType::PHP,
		ContentType::JS,
		ContentType::XML,
		ContentType::JSON,
		ContentType::PDF,
		ContentType::ZIP,
		ContentType::GZIP,
		ContentType::DOC,
		ContentType::XLS,
		ContentType::XLSX,
		ContentType::DOCX,
		ContentType::RAR,
		ContentType::TAR,
		ContentType::OCTET_STREAM,
		ContentType::TTF,
		ContentType::WOFF,
		ContentType::WOFF2,
		ContentType::FORM_DATA,
		ContentType::MULTIPART_MIXED,
	];

	expect(ContentType::cases())->toMatchArray($expectedCases);
});

it('cases have expected values', function (ContentType $case, string $expectedValue) {
	expect($case->value)->toBe($expectedValue);
})->with([
	[ContentType::TXT, 'text/plain'],
	[ContentType::HTML, 'text/html'],
	[ContentType::CSS, 'text/css'],
	[ContentType::CSV, 'text/csv'],
	[ContentType::JPG, 'image/jpeg'],
	[ContentType::PNG, 'image/png'],
	[ContentType::GIF, 'image/gif'],
	[ContentType::SVG, 'image/svg+xml'],
	[ContentType::WEBP, 'image/webp'],
	[ContentType::BMP, 'image/bmp'],
	[ContentType::TIFF, 'image/tiff'],
	[ContentType::MP3, 'audio/mpeg'],
	[ContentType::OGG_AUDIO, 'audio/ogg'],
	[ContentType::WAV, 'audio/wav'],
	[ContentType::AAC, 'audio/aac'],
	[ContentType::FLAC, 'audio/flac'],
	[ContentType::MP4, 'video/mp4'],
	[ContentType::MPEG, 'video/mpeg'],
	[ContentType::OGG_VIDEO, 'video/ogg'],
	[ContentType::WEBM, 'video/webm'],
	[ContentType::AVI, 'video/x-msvideo'],
	[ContentType::PHP, 'application/x-httpd-php'],
	[ContentType::JS, 'application/javascript'],
	[ContentType::XML, 'application/xml'],
	[ContentType::JSON, 'application/json'],
	[ContentType::PDF, 'application/pdf'],
	[ContentType::ZIP, 'application/zip'],
	[ContentType::GZIP, 'application/gzip'],
	[ContentType::DOC, 'application/msword'],
	[ContentType::XLS, 'application/vnd.ms-excel'],
	[ContentType::XLSX, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
	[ContentType::DOCX, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
	[ContentType::RAR, 'application/x-rar-compressed'],
	[ContentType::TAR, 'application/x-tar'],
	[ContentType::OCTET_STREAM, 'application/octet-stream'],
	[ContentType::TTF, 'font/ttf'],
	[ContentType::WOFF, 'font/woff'],
	[ContentType::WOFF2, 'font/woff2'],
	[ContentType::FORM_DATA, 'multipart/form-data'],
	[ContentType::MULTIPART_MIXED, 'multipart/mixed'],
]);
