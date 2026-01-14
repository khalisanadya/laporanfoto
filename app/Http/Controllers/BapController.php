<?php

namespace App\Http\Controllers;

use App\Models\Bap;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        try {
           
            if (ob_get_level()) { ob_end_clean(); }

            $phpWord = new PhpWord();
            $phpWord->setDefaultFontName('Arial');
            $phpWord->setDefaultFontSize(11);

            $section = $phpWord->addSection([
                'marginTop' => 1200, 'marginBottom' => 800, 'marginLeft' => 1200, 'marginRight' => 1200,
            ]);

           
            $header = $section->addHeader();
            $logoPath = public_path('images/logo-gasnet.png'); 
            if (file_exists($logoPath)) {
                $header->addImage($logoPath, ['width' => 100, 'height' => 35, 'alignment' => Jc::LEFT]);
            }

            
            $footer = $section->addFooter();
            $footerTable = $footer->addTable(['alignment' => Jc::CENTER]);
            $footerTable->addRow();
            $footerTable->addCell(4500)->addText('Paraf PT TME :', ['size' => 10, 'color' => '808080'], ['alignment' => Jc::LEFT]);
            $footerTable->addCell(4500)->addText('Paraf PT GASNET :', ['size' => 10, 'color' => '808080'], ['alignment' => Jc::RIGHT]);

            // Garis Biru di bawah header
            $section->addText('________________________________________________________________________________________________________', ['color' => '4A86E8', 'size' => 8]);
            $section->addTextBreak(1);

            // Judul
            $section->addText('BERITA ACARA PEMERIKSAAN', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
            $section->addText('JASA INSTALASI DAN MANAGED SERVICE ACCESS POINT (AP)', ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);
            $section->addText('PGNMAS SITE GS8 (JAKARTA) DAN KEBONWARU (BANDUNG)', ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);
            
            $section->addTextBreak(1);
            $section->addText('Nomor : ' . $bap->nomor_bap, ['bold' => true, 'color' => '2E5496'], ['alignment' => Jc::CENTER]);
            $section->addTextBreak(1);

            // Isi Berita Acara
            $tanggalBap = Carbon::parse($bap->tanggal_bap);
            $hari = $this->getHariIndonesia($tanggalBap->dayOfWeek);
            $bulan = $this->getBulanIndonesia($tanggalBap->month);

            $textRun = $section->addTextRun(['alignment' => Jc::BOTH, 'lineSpacing' => 1.5]);
            $textRun->addText('Pada hari ini, ');
            $textRun->addText($hari, ['bold' => true]);
            $textRun->addText(' tanggal ');
            $textRun->addText($tanggalBap->day, ['bold' => true]);
            $textRun->addText(' bulan ');
            $textRun->addText($bulan, ['bold' => true]);
            $textRun->addText(' tahun ');
            $textRun->addText($tanggalBap->year, ['bold' => true]);
            $textRun->addText(' (' . $tanggalBap->format('d-m-Y') . '), telah dilaksanakan pemeriksaan terhadap pekerjaan ');
            $textRun->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true]);
            $textRun->addText(' oleh:');

            $section->addTextBreak(1);

            // Tabel Pihak Pertama
            $tableStyle = ['cellMargin' => 50];
            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(2000)->addText('Nama'); $table->addCell(7000)->addText(': Angga Galih Perdana');
            $table->addRow();
            $table->addCell(2000)->addText('Jabatan'); $table->addCell(7000)->addText(': Dept Head Network Operation');
            $table->addRow();
            $table->addCell(2000)->addText('Perusahaan'); $table->addCell(7000)->addText(': PT Telemedia Dinamika Sarana');

            $section->addTextBreak(1);
            $section->addText('Selanjutnya disebut sebagai "Pihak Pertama", dan');
            $section->addTextBreak(1);

            // Tabel Pihak Kedua
            $table2 = $section->addTable($tableStyle);
            $table2->addRow();
            $table2->addCell(2000)->addText('Nama'); $table2->addCell(7000)->addText(': Nini Jaya');
            $table2->addRow();
            $table2->addCell(2000)->addText('Jabatan'); $table2->addCell(7000)->addText(': Direktur');
            $table2->addRow();
            $table2->addCell(2000)->addText('Perusahaan'); $table2->addCell(7000)->addText(': PT Telemedia Mitra Elektrotama');

            $section->addTextBreak(1);
            $section->addText('Selanjutnya disebut sebagai "Pihak Kedua".');

            // --- DASAR BERITA ACARA ---
            $section->addTextBreak(1);
            $section->addText('Berita Acara ini dibuat berdasarkan:', [], ['alignment' => Jc::BOTH]);
            $section->addTextBreak(1);

            // Tanggal Surat Permohonan
            $tglSurat = Carbon::parse($bap->tanggal_surat_permohonan);
            $bulanSurat = $this->getBulanIndonesia($tglSurat->month);

            // Point 1
            $p1 = $section->addTextRun(['alignment' => Jc::BOTH]);
            $p1->addText('1. ');
            $p1->addText('Surat Perintah Kerja', ['italic' => true, 'bold' => true]);
            $p1->addText(' yang dikeluarkan PT Telemedia Dinamika Sarana Nomor : ');
            $p1->addText('152600.SPK/LG.01.03/UT/2025', ['bold' => true]);
            $p1->addText(' tanggal ');
            $p1->addText('01 Oktober 2025', ['bold' => true]);
            $p1->addText(' untuk ');
            $p1->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true]);
            $p1->addText(' ("Kontrak");');

            // Point 2
            $p2 = $section->addTextRun(['alignment' => Jc::BOTH]);
            $p2->addText('2. Surat dari PT.Telemedia Mitra Elektrotama Nomor: ');
            $p2->addText($bap->nomor_surat_permohonan, ['bold' => true]);
            $p2->addText(' tanggal ');
            $p2->addText($tglSurat->day . ' ' . $bulanSurat . ' ' . $tglSurat->year, ['bold' => true]);
            $p2->addText(' perihal Surat Permohonan Pemeriksaan Pekerjaan;');

            // Point 3
            $p3 = $section->addTextRun(['alignment' => Jc::BOTH]);
            $p3->addText('3. ');
            $p3->addText('Laporan Pekerjaan', ['italic' => true, 'bold' => true]);
            $p3->addText(' Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung) Periode Desember 2025 dari pihak Kedua;');

            
            $section->addTextBreak(1);
            $section->addText('Dan berdasarkan hasil pemeriksaan maka Pihak Pertama dan Pihak Kedua menyimpulkan/menyetujui hal-hal sebagai berikut:', [], ['alignment' => Jc::BOTH]);
            $section->addTextBreak(1);

            // Hasil 1
            $h1 = $section->addTextRun(['alignment' => Jc::BOTH]);
            $h1->addText('1. Penyedia Jasa telah menyelesaikan pekerjaan ');
            $h1->addText('Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)', ['bold' => true]);
            $h1->addText(' periode Desember 2025 sesuai dengan syarat-syarat yang ditentukan dalam Surat Perintah Kerja.');

            // Hasil 2
            $h2 = $section->addTextRun(['alignment' => Jc::BOTH]);
            $h2->addText('2. Penyedia Jasa berhak menerima pembayaran periode Desember 2025 yaitu sebesar: ');
            $h2->addText('Rp. 8.900.000,- (Delapan Juta Sembilan Ratus Ribu Rupiah)', ['bold' => true]);
            $h2->addText(', belum termasuk PPN dan pajak – pajak yang berlaku sesuai ketentuan.');

            // --- PENUTUP ---
            $section->addTextBreak(1);
            $section->addText('Demikian Berita Acara ini dibuat rangkap 2 (dua) dan ditandatangani untuk dapat diketahui serta dipergunakan sebagaimana mestinya.', [], ['alignment' => Jc::BOTH]);

            // Tanda Tangan
            $section->addTextBreak(2);
            $signTable = $section->addTable(['alignment' => Jc::CENTER]);
            $signTable->addRow();
            $signTable->addCell(4500)->addText('PT Telemedia Mitra Elektrotama', [], ['alignment' => Jc::CENTER]);
            $signTable->addCell(4500)->addText('PT Telemedia Dinamika Sarana', [], ['alignment' => Jc::CENTER]);
            $signTable->addRow();
            $signTable->addCell(4500)->addText('Direktur', [], ['alignment' => Jc::CENTER]);
            $signTable->addCell(4500)->addText('Dept Head Network Operation', [], ['alignment' => Jc::CENTER]);
            $signTable->addRow(1200); 
            $signTable->addCell(4500); $signTable->addCell(4500);
            $signTable->addRow();
            $signTable->addCell(4500)->addText('Nini Jaya', ['bold' => true], ['alignment' => Jc::CENTER]);
            $signTable->addCell(4500)->addText('Angga Galih Perdana', ['bold' => true], ['alignment' => Jc::CENTER]);

            // Proses Pengiriman File
            $fileName = "BAP-" . preg_replace('/[^a-zA-Z0-9]/', '-', $bap->nomor_bap) . ".docx";
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
            exit;

        } catch (\Exception $e) {
            Log::error("Gagal generate Word: " . $e->getMessage());
            return "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }

    private function getHariIndonesia($dayOfWeek)
    {
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $hari[$dayOfWeek];
    }

    private function getBulanIndonesia($month)
    {
        $bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        return $bulan[$month];
    }
}