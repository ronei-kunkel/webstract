<?php

declare(strict_types=1);

namespace Test\Common;

use PHPUnit\Framework\Attributes\DataProvider;
use Test\TestCase;
use Webstract\Common\HttpContentType;

class HttpContentTypeTest extends TestCase
{
	public function test_AssertContentTypeHeaderName(): void
	{
		$this->assertSame('Content-Type', HttpContentType::getHeaderName());
	}

	public static function provideFileAndExpectedContentType(): iterable
	{
		yield ['fileName' => 'file.txt', 'expectedContentType' => HttpContentType::TXT];
		yield ['fileName' => 'document.html', 'expectedContentType' => HttpContentType::HTML];
		yield ['fileName' => 'image.jpg', 'expectedContentType' => HttpContentType::JPG];
		yield ['fileName' => 'audio.mp3', 'expectedContentType' => HttpContentType::MP3];
		yield ['fileName' => 'video.mp4', 'expectedContentType' => HttpContentType::MP4];
		yield ['fileName' => 'archive.zip', 'expectedContentType' => HttpContentType::ZIP];
		yield ['fileName' => 'sheet.xlsx', 'expectedContentType' => HttpContentType::XLSX];
		yield ['fileName' => 'font.woff2', 'expectedContentType' => HttpContentType::WOFF2];
		yield ['fileName' => 'script.js', 'expectedContentType' => HttpContentType::JS];
		yield ['fileName' => 'index.php', 'expectedContentType' => HttpContentType::PHP];
	}

	#[DataProvider('provideFileAndExpectedContentType')]
	public function test_AssertContentTypeValue(string $fileName, HttpContentType $expectedContentType): void
	{
		$this->assertSame($expectedContentType, HttpContentType::fromFilename($fileName));
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
		$this->assertNull(HttpContentType::fromFilename($fileName));
	}

	public static function provideAllCases(): iterable
	{
		yield [[
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
		]];
	}

	#[DataProvider('provideAllCases')]
	public function test_AssertAllCases(array $allCases): void
	{
		$this->assertSame($allCases, HttpContentType::cases());
	}

	public static function provideAllCasesAndValues(): iterable
	{
		yield [HttpContentType::TXT, 'text/plain'];
		yield [HttpContentType::HTML, 'text/html'];
		yield [HttpContentType::CSS, 'text/css'];
		yield [HttpContentType::CSV, 'text/csv'];
		yield [HttpContentType::JPG, 'image/jpeg'];
		yield [HttpContentType::PNG, 'image/png'];
		yield [HttpContentType::GIF, 'image/gif'];
		yield [HttpContentType::SVG, 'image/svg+xml'];
		yield [HttpContentType::WEBP, 'image/webp'];
		yield [HttpContentType::BMP, 'image/bmp'];
		yield [HttpContentType::TIFF, 'image/tiff'];
		yield [HttpContentType::MP3, 'audio/mpeg'];
		yield [HttpContentType::OGG_AUDIO, 'audio/ogg'];
		yield [HttpContentType::WAV, 'audio/wav'];
		yield [HttpContentType::AAC, 'audio/aac'];
		yield [HttpContentType::FLAC, 'audio/flac'];
		yield [HttpContentType::MP4, 'video/mp4'];
		yield [HttpContentType::MPEG, 'video/mpeg'];
		yield [HttpContentType::OGG_VIDEO, 'video/ogg'];
		yield [HttpContentType::WEBM, 'video/webm'];
		yield [HttpContentType::AVI, 'video/x-msvideo'];
		yield [HttpContentType::PHP, 'application/x-httpd-php'];
		yield [HttpContentType::JS, 'application/javascript'];
		yield [HttpContentType::XML, 'application/xml'];
		yield [HttpContentType::JSON, 'application/json'];
		yield [HttpContentType::PDF, 'application/pdf'];
		yield [HttpContentType::ZIP, 'application/zip'];
		yield [HttpContentType::GZIP, 'application/gzip'];
		yield [HttpContentType::DOC, 'application/msword'];
		yield [HttpContentType::XLS, 'application/vnd.ms-excel'];
		yield [HttpContentType::XLSX, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		yield [HttpContentType::DOCX, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
		yield [HttpContentType::RAR, 'application/x-rar-compressed'];
		yield [HttpContentType::TAR, 'application/x-tar'];
		yield [HttpContentType::OCTET_STREAM, 'application/octet-stream'];
		yield [HttpContentType::TTF, 'font/ttf'];
		yield [HttpContentType::WOFF, 'font/woff'];
		yield [HttpContentType::WOFF2, 'font/woff2'];
		yield [HttpContentType::FORM_DATA, 'multipart/form-data'];
		yield [HttpContentType::MULTIPART_MIXED, 'multipart/mixed'];
	}

	#[DataProvider('provideAllCasesAndValues')]
	public function test_AssertAllCaseValues(HttpContentType $case, string $expectedValue): void
	{
		$this->assertSame($expectedValue, $case->value);
	}
}
