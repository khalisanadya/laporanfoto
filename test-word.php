<?php
require 'vendor/autoload.php';

$word = new PhpOffice\PhpWord\PhpWord();
$section = $word->addSection();
$section->addText('Test BAP');

$writer = PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
$writer->save('storage/app/test-bap.docx');

echo "SUCCESS - File saved to storage/app/test-bap.docx\n";
