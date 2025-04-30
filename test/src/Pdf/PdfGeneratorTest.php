<?php

declare(strict_types=1);

use Test\Support\Pdf\DompdfPdfGenerator;
use Test\Support\Pdf\FakePdfContent;
use Test\Support\TemplateEngine\TwigTemplateEngineRenderer;

test('should work properly', function () {
	$content = new FakePdfContent('a');
	$templateEngine = new TwigTemplateEngineRenderer();
	$generator = new DompdfPdfGenerator($templateEngine);

	$return = $generator->generateContent($content);

	expect($return)->toContain(
		<<<PDF
		%PDF-1.7
		1 0 obj
		<< /Type /Catalog
		/Outlines 2 0 R
		/Pages 3 0 R >>
		endobj
		2 0 obj
		<< /Type /Outlines /Count 0 >>
		endobj
		3 0 obj
		<< /Type /Pages
		/Kids [6 0 R
		]
		/Count 1
		/Resources <<
		/ProcSet 4 0 R
		/Font << 
		/F1 8 0 R
		>>
		>>
		/MediaBox [0.000 0.000 612.000 792.000]
		 >>
		endobj
		4 0 obj
		[/PDF /Text ]
		endobj
		5 0 obj
		<<
		PDF,
		<<<PDF
		>>
		endobj
		6 0 obj
		<< /Type /Page
		/MediaBox [0.000 0.000 612.000 792.000]
		/Parent 3 0 R
		/Contents 7 0 R
		>>
		endobj
		7 0 obj
		<< /Filter /FlateDecode
		/Length 63 >>
		stream
		PDF,
		<<<PDF
		endstream
		endobj
		8 0 obj
		<< /Type /Font
		/Subtype /Type1
		/Name /F1
		/BaseFont /Times-Roman
		/Encoding /WinAnsiEncoding
		>>
		endobj
		xref
		0 9
		0000000000 65535 f 
		0000000009 00000 n 
		0000000074 00000 n 
		0000000120 00000 n 
		0000000274 00000 n 
		0000000303 00000 n 
		0000000452 00000 n 
		0000000555 00000 n 
		0000000689 00000 n 
		trailer
		<<
		/Size 9
		/Root 1 0 R
		/Info 5 0 R
		PDF,
		<<<PDF
		>>
		startxref
		798
		%%EOF

		PDF
	);
});
