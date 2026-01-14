<?php
// Test BAP Word generation
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

echo "Starting...\n";

try {
    $phpWord = new PhpWord();
    $phpWord->setDefaultFontName('Arial');
    $phpWord->setDefaultFontSize(11);

    $section = $phpWord->addSection([
        'marginTop' => 800,
        'marginBottom' => 800,
        'marginLeft' => 1200,
        'marginRight' => 1200,
    ]);

    // Title
    $section->addText(
        'BERITA ACARA PEMERIKSAAN',
        ['bold' => true, 'size' => 14],
        ['alignment' => Jc::CENTER]
    );
    
    $section->addText(
        'JASA INSTALASI DAN MANAGED SERVICE ACCESS POINT (AP)',
        ['bold' => true, 'size' => 12],
        ['alignment' => Jc::CENTER]
    );
    
    $section->addText(
        'PGNMAS SITE GS8 (JAKARTA) DAN KEBONWARU (BANDUNG)',
        ['bold' => true, 'size' => 12],
        ['alignment' => Jc::CENTER]
    );

    $section->addTextBreak(1);

    $section->addText(
        'Nomor : 000100.BA-OP.01.00-NOR-2026',
        ['bold' => true, 'size' => 11],
        ['alignment' => Jc::CENTER]
    );

    $section->addTextBreak(1);

    // Simple paragraph
    $textRun = $section->addTextRun(['alignment' => Jc::BOTH]);
    $textRun->addText('Pada hari ini, ');
    $textRun->addText('Senin', ['bold' => true]);
    $textRun->addText(' tanggal ');
    $textRun->addText('Tiga Belas', ['bold' => true]);
    $textRun->addText(' bulan ');
    $textRun->addText('Januari', ['bold' => true]);
    $textRun->addText(' tahun ');
    $textRun->addText('Dua Ribu Dua Puluh Enam', ['bold' => true]);
    $textRun->addText(' (13-01-2026), telah dilaksanakan pemeriksaan.');

    $section->addTextBreak(1);

    // Table
    $table = $section->addTable();
    $table->addRow();
    $table->addCell(2000)->addText('Nama');
    $table->addCell(7000)->addText(': Angga Galih Perdana');
    
    $table->addRow();
    $table->addCell(2000)->addText('Jabatan');
    $table->addCell(7000)->addText(': Department Head');
    
    $table->addRow();
    $table->addCell(2000)->addText('Perusahaan');
    $table->addCell(7000)->addText(': PT Telemedia Dinamika Sarana');

    $section->addTextBreak(1);
    $section->addText('Selanjutnya disebut sebagai "Pihak Pertama".');

    // Save
    $filename = 'storage/app/test-bap-controller.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($filename);
    
    $size = filesize($filename);
    echo "SUCCESS! File saved: {$filename}\n";
    echo "File size: {$size} bytes\n";
    
    // Copy to Downloads
    $downloadPath = getenv('USERPROFILE') . '\\Downloads\\TEST-BAP.docx';
    copy($filename, $downloadPath);
    echo "Copied to: {$downloadPath}\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
