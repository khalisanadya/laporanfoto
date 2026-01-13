<?php

namespace App\Http\Controllers;

use App\Models\Bap;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Carbon\Carbon;

class BapController extends Controller
{
    public function index()
    {
        $myBapIds = request()->get('my_bap_ids', []);
        $baps = Bap::whereIn('id', $myBapIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('bap.index', compact('baps'));
    }

    public function create()
    {
        return view('bap.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_bap' => 'required|date',
            'nomor_bap' => 'required|string',
            'nomor_surat_permohonan' => 'required|string',
            'tanggal_surat_permohonan' => 'required|date',
        ]);

        $bap = Bap::create($validated);

        // Add to cookie for device tracking
        $myReports = $request->cookie('my_baps', '');
        $reportIds = $myReports ? explode(',', $myReports) : [];
        $reportIds[] = $bap->id;
        $cookie = cookie('my_baps', implode(',', $reportIds), 60 * 24 * 365);

        return redirect()->route('bap.show', $bap)
            ->with('success', 'BAP berhasil dibuat!')
            ->withCookie($cookie);
    }

    public function show(Bap $bap)
    {
        return view('bap.show', compact('bap'));
    }

    public function word(Bap $bap)
    {
        $phpWord = new PhpWord();
        
        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1200,
            'marginRight' => 1200,
        ]);

        // Logo - temporarily disabled for testing
        // $logoPath = public_path('images/logo-gasnet.png');
        // if (file_exists($logoPath)) {
        //     $section->addImage($logoPath, [
        //         'width' => 80,
        //         'height' => 40,
        //         'alignment' => Jc::START,
        //     ]);
        // }

        $section->addTextBreak(1);

        // Title
        $section->addText(
            'BERITA ACARA PEMERIKSAAN',
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
        $section->addText(
            'JASA INSTALASI DAN MANAGED SERVICE ACCESS POINT (AP)',
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
        $section->addText(
            'PGNMAS SITE GS8 (JAKARTA) DAN KEBONWARU (BANDUNG)',
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );

        // Nomor BAP
        $section->addText(
            'Nomor : ' . $bap->nomor_bap,
            ['bold' => true, 'size' => 11],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 300]
        );

        // Format tanggal Indonesia
        $tanggalBap = Carbon::parse($bap->tanggal_bap);
        $hariIndo = $this->getHariIndonesia($tanggalBap->dayOfWeek);
        $bulanIndo = $this->getBulanIndonesia($tanggalBap->month);
        $tanggalTerbilang = $this->terbilang($tanggalBap->day);
        $tahunTerbilang = $this->terbilangTahun($tanggalBap->year);

        // Paragraph 1
        $textRun = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 200]);
        $textRun->addText('Pada hari ini, ');
        $textRun->addText($hariIndo, ['bold' => true]);
        $textRun->addText(' tanggal ');
        $textRun->addText(ucfirst($tanggalTerbilang), ['bold' => true]);
        $textRun->addText(' bulan ');
        $textRun->addText($bulanIndo, ['bold' => true]);
        $textRun->addText(' tahun ');
        $textRun->addText($tahunTerbilang, ['bold' => true]);
        $textRun->addText(' (');
        $textRun->addText($tanggalBap->format('d-m-Y'), ['bold' => true]);
        $textRun->addText('), telah dilaksanakan pemeriksaan terhadap pekerjaan ');
        $textRun->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true]);
        $textRun->addText(' oleh:');

        $section->addTextBreak(1);

        // Pihak Pertama
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

        $section->addTextBreak(1);
        $section->addText('Selanjutnya disebut sebagai "Pihak Pertama", dan', ['size' => 11]);
        $section->addTextBreak(1);

        // Pihak Kedua
        $table2 = $section->addTable();
        $table2->addRow();
        $table2->addCell(2000)->addText('Nama', ['size' => 11]);
        $table2->addCell(7000)->addText(': Nini Jaya', ['size' => 11]);
        
        $table2->addRow();
        $table2->addCell(2000)->addText('Jabatan', ['size' => 11]);
        $table2->addCell(7000)->addText(': Direktur', ['size' => 11]);
        
        $table2->addRow();
        $table2->addCell(2000)->addText('Perusahaan', ['size' => 11]);
        $table2->addCell(7000)->addText(': PT Telemedia Mitra Elektrotama', ['size' => 11]);

        $section->addTextBreak(1);
        $section->addText('Selanjutnya disebut sebagai "Pihak Kedua".', ['size' => 11]);
        $section->addTextBreak(1);

        // Berita Acara berdasarkan
        $section->addText('Berita Acara ini dibuat berdasarkan:', ['size' => 11]);

        // List items
        $listRun1 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 100]);
        $listRun1->addText('1.  ', ['size' => 11]);
        $listRun1->addText('Surat Perintah Kerja', ['bold' => true, 'italic' => true, 'size' => 11]);
        $listRun1->addText(' yang dikeluarkan PT Telemedia Dinamika Sarana Nomor : ', ['size' => 11]);
        $listRun1->addText('152600.SPK/LG.01.03/UT/2025', ['bold' => true, 'size' => 11]);
        $listRun1->addText(' tanggal ', ['size' => 11]);
        $listRun1->addText('01 Oktober 2025', ['bold' => true, 'size' => 11]);
        $listRun1->addText(' untuk ', ['size' => 11]);
        $listRun1->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true, 'size' => 11]);
        $listRun1->addText(' ("Kontrak");', ['size' => 11]);

        $tanggalSuratPermohonan = Carbon::parse($bap->tanggal_surat_permohonan);
        $listRun2 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 100]);
        $listRun2->addText('2.  ', ['size' => 11]);
        $listRun2->addText('Surat dari PT.Telemedia Mitra Elektrotama Nomor: ', ['size' => 11]);
        $listRun2->addText($bap->nomor_surat_permohonan, ['bold' => true, 'size' => 11]);
        $listRun2->addText(' tanggal ', ['size' => 11]);
        $listRun2->addText($tanggalSuratPermohonan->format('d') . ' ' . $this->getBulanIndonesia($tanggalSuratPermohonan->month) . ' ' . $tanggalSuratPermohonan->year, ['bold' => true, 'size' => 11]);
        $listRun2->addText(' perihal Surat Permohonan Pemeriksaan Pekerjaan;', ['size' => 11]);

        $listRun3 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 100]);
        $listRun3->addText('3.  ', ['size' => 11]);
        $listRun3->addText('Laporan Pekerjaan', ['bold' => true, 'italic' => true, 'size' => 11]);
        $listRun3->addText(' Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung) Periode Desember 2025 dari pihak Kedua;', ['size' => 11]);

        // Paraf section
        $section->addTextBreak(2);
        $parafTable = $section->addTable(['alignment' => Jc::CENTER]);
        $parafTable->addRow();
        $parafTable->addCell(4500)->addText('Paraf PT TME :', ['size' => 10]);
        $parafTable->addCell(4500)->addText('Paraf PT GASNET :', ['size' => 10], ['alignment' => Jc::END]);

        // New page for second part
        $section->addPageBreak();

        // Logo again - temporarily disabled
        // if (file_exists($logoPath)) {
        //     $section->addImage($logoPath, [
        //         'width' => 80,
        //         'height' => 40,
        //         'alignment' => Jc::START,
        //     ]);
        // }

        $section->addTextBreak(2);

        // Hasil pemeriksaan
        $textRun2 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 200]);
        $textRun2->addText('Dan berdasarkan hasil pemeriksaan maka Pihak Pertama dan Pihak Kedua menyimpulkan/menyetujui hal-hal sebagai berikut:', ['size' => 11]);

        $section->addTextBreak(1);

        $listRun4 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 100]);
        $listRun4->addText('1.  ', ['size' => 11]);
        $listRun4->addText('Penyedia Jasa telah menyelesaikan pekerjaan ', ['size' => 11]);
        $listRun4->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true, 'size' => 11]);
        $listRun4->addText(' periode Desember 2025 sesuai dengan syarat-syarat yang ditentukan dalam Surat Perintah Kerja.', ['size' => 11]);

        $listRun5 = $section->addTextRun(['alignment' => Jc::BOTH, 'spaceAfter' => 100]);
        $listRun5->addText('2.  ', ['size' => 11]);
        $listRun5->addText('Penyedia Jasa berhak menerima pembayaran periode Desember 2025 yaitu sebesar: ', ['size' => 11]);
        $listRun5->addText('Rp. 8.900.000,- (Delapan Juta Sembilan Ratus Ribu Rupiah)', ['bold' => true, 'size' => 11]);
        $listRun5->addText(', belum termasuk PPN dan pajak – pajak yang berlaku sesuai ketentuan.', ['size' => 11]);

        $section->addTextBreak(2);

        $section->addText(
            'Demikian Berita Acara ini dibuat rangkap 2 (dua) dan ditandatangani untuk dapat diketahui serta dipergunakan sebagaimana mestinya.',
            ['size' => 11],
            ['alignment' => Jc::BOTH]
        );

        $section->addTextBreak(3);

        // Signature table
        $signTable = $section->addTable(['alignment' => Jc::CENTER]);
        $signTable->addRow();
        $cell1 = $signTable->addCell(4500, ['valign' => 'top']);
        $cell1->addText('PT Telemedia Mitra Elektrotama', ['size' => 11], ['alignment' => Jc::CENTER]);
        $cell1->addText('Direktur', ['size' => 11], ['alignment' => Jc::CENTER]);
        
        $cell2 = $signTable->addCell(4500, ['valign' => 'top']);
        $cell2->addText('PT Telemedia Dinamika Sarana', ['size' => 11], ['alignment' => Jc::CENTER]);
        $cell2->addText('Department Head Network Operation & Reliability', ['size' => 11], ['alignment' => Jc::CENTER]);

        $signTable->addRow(1500);
        $signTable->addCell(4500)->addText('');
        $signTable->addCell(4500)->addText('');

        $signTable->addRow();
        $signTable->addCell(4500)->addText('Nini Jaya', ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);
        $signTable->addCell(4500)->addText('Angga Galih Perdana', ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);

        // Save file
        $filename = "BAP-{$bap->id}.docx";
        $temp = storage_path("app/{$filename}");
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($temp);

        $downloadName = "BAP-" . preg_replace('/[^a-zA-Z0-9\-\.]/', '-', $bap->nomor_bap) . ".docx";
        return response()->download($temp, $downloadName)->deleteFileAfterSend(true);
    }

    private function getHariIndonesia($dayOfWeek)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $hari[$dayOfWeek];
    }

    private function getBulanIndonesia($month)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$month];
    }

    private function terbilang($n)
    {
        $angka = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        
        if ($n < 12) {
            return $angka[$n];
        } elseif ($n < 20) {
            return $angka[$n - 10] . ' belas';
        } elseif ($n < 100) {
            return $angka[floor($n / 10)] . ' puluh ' . $angka[$n % 10];
        }
        return $n;
    }

    private function terbilangTahun($tahun)
    {
        // 2026 = Dua Ribu Dua Puluh Enam
        $ribuan = floor($tahun / 1000);
        $sisa = $tahun % 1000;
        $ratusan = floor($sisa / 100);
        $puluhan = $sisa % 100;

        $result = '';
        if ($ribuan == 2) {
            $result .= 'Dua Ribu ';
        }
        if ($ratusan > 0) {
            $angka = ['', 'Seratus', 'Dua Ratus', 'Tiga Ratus', 'Empat Ratus', 'Lima Ratus', 'Enam Ratus', 'Tujuh Ratus', 'Delapan Ratus', 'Sembilan Ratus'];
            $result .= $angka[$ratusan] . ' ';
        }
        if ($puluhan > 0) {
            $result .= ucfirst($this->terbilang($puluhan));
        }

        return trim($result) . ' (' . $tahun . ')';
    }
}
