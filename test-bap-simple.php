<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

$phpWord = new PhpWord();
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
    'marginTop' => 800,
    'marginBottom' => 800,
    'marginLeft' => 1200,
    'marginRight' => 1200,
]);

$section->addText('BERITA ACARA PEMERIKSAAN', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
$section->addText('JASA INSTALASI DAN MANAGED SERVICE ACCESS POINT (AP)', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('PGNMAS SITE GS8 (JAKARTA) DAN KEBONWARU (BANDUNG)', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

$section->addText('Nomor : 000100.BA-OP.01.00-NOR-2026', ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);

$section->addTextBreak(2);

$textRun = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 200]);
$textRun->addText('Pada hari ini, ');
$textRun->addText('Senin', ['bold' => true]);
$textRun->addText(' tanggal ');
$textRun->addText('Tiga Belas', ['bold' => true]);
$textRun->addText(' bulan ');
$textRun->addText('Januari', ['bold' => true]);
$textRun->addText(' tahun ');
$textRun->addText('Dua Ribu Dua Puluh Enam', ['bold' => true]);
$textRun->addText(' (');
$textRun->addText('13-01-2026', ['bold' => true]);
$textRun->addText('), telah dilaksanakan pemeriksaan terhadap pekerjaan ');
$textRun->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true]);
$textRun->addText(' oleh:');

$section->addTextBreak(1);

// Table
$table = $section->addTable();
$table->addRow();
$table->addCell(2000)->addText('Nama', ['size' => 11]);
$table->addCell(7000)->addText(': Angga Galih Perdana', ['size' => 11]);

$table->addRow();
$table->addCell(2000)->addText('Jabatan', ['size' => 11]);
$table->addCell(7000)->addText(': Department Head Network Operation & Reliability', ['size' => 11]);

$table->addRow();
$table->addCell(2000)->addText('Perusahaan', ['size' => 11]);
$table->addCell(7000)->addText(': PT Telemedia Dinamika Sarana', ['size' => 11]);

$section->addTextBreak(2);
$section->addText('Selanjutnya disebut sebagai "Pihak Pertama".', ['size' => 11]);

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('storage/app/bap-simple-test.docx');

echo "SUCCESS - File saved to storage/app/bap-simple-test.docx\n";
echo "File size: " . filesize('storage/app/bap-simple-test.docx') . " bytes\n";
