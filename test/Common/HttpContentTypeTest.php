<?php

declare(strict_types=1);

namespace Test\Common;

use PHPUnit\Framework\Attributes\DataProvider;
use Test\TestCase;
use Webstract\Http\ContentType;

class HttpContentTypeTest extends TestCase
{
	public function test_AssertContentTypeHeaderName(): void
	{
		$this->assertSame('Content-Type', ContentType::getHeaderName());
	}

	public static function provideFileAndExpectedContentType(): iterable
	{
		yield ['fileName' => 'file.txt', 'expectedContentType' => ContentType::TXT];
		yield ['fileName' => 'document.html', 'expectedContentType' => ContentType::HTML];
		yield ['fileName' => 'image.jpg', 'expectedContentType' => ContentType::JPG];
		yield ['fileName' => 'audio.mp3', 'expectedContentType' => ContentType::MP3];
		yield ['fileName' => 'video.mp4', 'expectedContentType' => ContentType::MP4];
		yield ['fileName' => 'archive.zip', 'expectedContentType' => ContentType::ZIP];
		yield ['fileName' => 'sheet.xlsx', 'expectedContentType' => ContentType::XLSX];
		yield ['fileName' => 'font.woff2', 'expectedContentType' => ContentType::WOFF2];
		yield ['fileName' => 'script.js', 'expectedContentType' => ContentType::JS];
		yield ['fileName' => 'index.php', 'expectedContentType' => ContentType::PHP];
	}

	#[DataProvider('provideFileAndExpectedContentType')]
	public function test_AssertContentTypeValue(string $fileName, ContentType $expectedContentType): void
	{
		$this->assertSame($expectedContentType, ContentType::fromFilename($fileName));
	}

	public static function provideFilesWithInvalidExtensions(): iterable
	{
		yield ['fileName' => 'unknown.filetype'];
		yield ['fileName' => 'noextension'];
		yield ['fileName' => 'image.jpeg2000'];
		yield ['fileName' => '.hiddenfile'];
		yield ['fileName' => 'unknowncase.INVALID'];
	}

	#[DataProvider('provideFilesWithInvalidExtensions')]
	public function test_AssertContentTypeValueNullForInvalidExtensions(string $fileName): void
	{
		$this->assertNull(ContentType::fromFilename($fileName));
	}

	public static function provideAllCases(): iterable
	{
		yield [[
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
		]];
	}

	#[DataProvider('provideAllCases')]
	public function test_AssertAllCases(array $allCases): void
	{
		$this->assertSame($allCases, ContentType::cases());
	}

	public static function provideAllCasesAndValues(): iterable
	{
		yield [ContentType::TXT, 'text/plain'];
		yield [ContentType::HTML, 'text/html'];
		yield [ContentType::CSS, 'text/css'];
		yield [ContentType::CSV, 'text/csv'];
		yield [ContentType::JPG, 'image/jpeg'];
		yield [ContentType::PNG, 'image/png'];
		yield [ContentType::GIF, 'image/gif'];
		yield [ContentType::SVG, 'image/svg+xml'];
		yield [ContentType::WEBP, 'image/webp'];
		yield [ContentType::BMP, 'image/bmp'];
		yield [ContentType::TIFF, 'image/tiff'];
		yield [ContentType::MP3, 'audio/mpeg'];
		yield [ContentType::OGG_AUDIO, 'audio/ogg'];
		yield [ContentType::WAV, 'audio/wav'];
		yield [ContentType::AAC, 'audio/aac'];
		yield [ContentType::FLAC, 'audio/flac'];
		yield [ContentType::MP4, 'video/mp4'];
		yield [ContentType::MPEG, 'video/mpeg'];
		yield [ContentType::OGG_VIDEO, 'video/ogg'];
		yield [ContentType::WEBM, 'video/webm'];
		yield [ContentType::AVI, 'video/x-msvideo'];
		yield [ContentType::PHP, 'application/x-httpd-php'];
		yield [ContentType::JS, 'application/javascript'];
		yield [ContentType::XML, 'application/xml'];
		yield [ContentType::JSON, 'application/json'];
		yield [ContentType::PDF, 'application/pdf'];
		yield [ContentType::ZIP, 'application/zip'];
		yield [ContentType::GZIP, 'application/gzip'];
		yield [ContentType::DOC, 'application/msword'];
		yield [ContentType::XLS, 'application/vnd.ms-excel'];
		yield [ContentType::XLSX, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		yield [ContentType::DOCX, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
		yield [ContentType::RAR, 'application/x-rar-compressed'];
		yield [ContentType::TAR, 'application/x-tar'];
		yield [ContentType::OCTET_STREAM, 'application/octet-stream'];
		yield [ContentType::TTF, 'font/ttf'];
		yield [ContentType::WOFF, 'font/woff'];
		yield [ContentType::WOFF2, 'font/woff2'];
		yield [ContentType::FORM_DATA, 'multipart/form-data'];
		yield [ContentType::MULTIPART_MIXED, 'multipart/mixed'];
	}

	#[DataProvider('provideAllCasesAndValues')]
	public function test_AssertAllCaseValues(ContentType $case, string $expectedValue): void
	{
		$this->assertSame($expectedValue, $case->value);
	}
}
