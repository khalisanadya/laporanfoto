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
        // Kode ini untuk menampilkan daftar BAP
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

        // Tracking via cookie
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
            // Bersihkan buffer agar tidak korup
            if (ob_get_level()) { ob_end_clean(); }

            $phpWord = new PhpWord();
            $phpWord->setDefaultFontName('Arial');
            $phpWord->setDefaultFontSize(11);

            $section = $phpWord->addSection([
                'marginTop' => 800, 'marginBottom' => 800, 'marginLeft' => 1200, 'marginRight' => 1200,
            ]);

            // Header Logo
            $logoPath = public_path('images/logo-gasnet.png'); 
            if (file_exists($logoPath)) {
                $section->addImage($logoPath, ['width' => 100, 'height' => 35, 'alignment' => Jc::LEFT]);
            }

            // Garis Biru
            $section->addLine(['width' => 450, 'height' => 0, 'weight' => 2, 'color' => '4A86E8']);
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