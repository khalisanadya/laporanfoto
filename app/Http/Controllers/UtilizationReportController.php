<?php

namespace App\Http\Controllers;

use App\Models\UtilizationReport;
use App\Models\UtilizationSection;
use App\Models\UtilizationItem;
use App\Models\UtilizationSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UtilizationReportController extends Controller
{
    public function index(Request $request)
    {
        $myIds = $request->cookie('my_utilization_reports');
        $myIds = $myIds ? explode(',', $myIds) : [];

        $reports = UtilizationReport::whereIn('id', $myIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('utilization.index', compact('reports'));
    }

    public function create()
    {
        return view('utilization.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $report = UtilizationReport::create($validated);

        if ($request->has('sections')) {
            foreach ($request->sections as $sectionIndex => $sectionData) {
                $section = UtilizationSection::create([
                    'utilization_report_id' => $report->id,
                    'nama_section' => $sectionData['nama'] ?? 'Section ' . ($sectionIndex + 1),
                    'warna_header' => $sectionData['warna'] ?? '#FFA500',
                    'urutan' => $sectionIndex,
                ]);

                if (isset($sectionData['items'])) {
                    foreach ($sectionData['items'] as $itemIndex => $itemData) {
                        $gambarPath = null;
                        $gambarInput = "sections.{$sectionIndex}.items.{$itemIndex}.gambar";
                        
                        if ($request->hasFile($gambarInput)) {
                            $gambar = $request->file($gambarInput);
                            if (is_array($gambar)) { $gambar = $gambar[0]; }
                            $gambarPath = $gambar->store('utilization-graphs', 'public');
                        }

                        UtilizationItem::create([
                            'utilization_section_id' => $section->id,
                            'nama_interface' => $itemData['nama_interface'] ?? null,
                            'label' => $itemData['label_caption'] ?? ($itemData['label'] ?? null),
                            'inbound_value' => $itemData['label_inbound'] ?? ($itemData['inbound_value'] ?? null),
                            'outbound_value' => $itemData['label_outbound'] ?? ($itemData['outbound_value'] ?? null),
                            'gambar_graph' => $gambarPath,
                            'urutan' => $itemIndex,
                        ]);
                    }
                }

                if (isset($sectionData['summaries'])) {
                    foreach ($sectionData['summaries'] as $sumIndex => $sumData) {
                        UtilizationSummary::create([
                            'utilization_section_id' => $section->id,
                            'kategori' => $sumData['kategori'] ?? '',
                            'inbound_value' => $sumData['inbound_value'] ?? null,
                            'outbound_value' => $sumData['outbound_value'] ?? null,
                            'urutan' => $sumIndex,
                        ]);
                    }
                }
            }
        }

        $myReports = $request->cookie('my_utilization_reports', '');
        $reportIds = $myReports ? explode(',', $myReports) : [];
        $reportIds[] = $report->id;
        $cookie = cookie('my_utilization_reports', implode(',', $reportIds), 60 * 24 * 365);

        return redirect()->route('utilization.show', $report)
            ->with('success', 'Utilization Report berhasil dibuat!')
            ->withCookie($cookie);
    }

    public function show(UtilizationReport $utilization)
    {
        $utilization->load(['sections.items', 'sections.summaries']);
        return view('utilization.show', compact('utilization'));
    }

    public function excel(UtilizationReport $utilization)
    {
        $utilization->load(['sections.items', 'sections.summaries']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Utilization Report');

       
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(10); 
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(18);
        
        
        foreach (range('F', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(15);
        }

        $row = 1;

     
        $sheet->setCellValue('A' . $row, strtoupper($utilization->judul));
        $sheet->mergeCells('A' . $row . ':E' . $row); // Merge A sampai E sesuai lebar konten
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        
        $periodeText = 'Periode: ' . $utilization->periode_mulai->translatedFormat('d F Y') . ' - ' . $utilization->periode_selesai->translatedFormat('d F Y');
        $sheet->setCellValue('A' . $row, $periodeText);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        foreach ($utilization->sections as $section) {
        
            $sheet->setCellValue('A' . $row, $section->nama_section);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB(str_replace('#', '', $section->warna_header));
            $row += 2; 
            $items = $section->items->chunk(2); 
            
            foreach ($items as $chunk) {
                $startRowThisChunk = $row;
                $maxRowReached = $row;

                foreach ($chunk as $index => $item) {
                    $isLeft = ($index % 2 == 0);
                    $colLabel = $isLeft ? 'A' : 'D'; // Loncat kolom C sebagai spacer
                    $colValue = $isLeft ? 'B' : 'E';

                    // 1. Gambar
                    if ($item->gambar_graph && Storage::disk('public')->exists($item->gambar_graph)) {
                        $imagePath = Storage::disk('public')->path($item->gambar_graph);
                        $drawing = new Drawing();
                        $drawing->setPath($imagePath);
                        $drawing->setHeight(160); // Ukuran gambar sedikit lebih besar
                        $drawing->setCoordinates($colLabel . $startRowThisChunk);
                        $drawing->setWorksheet($sheet);
                        
                        $labelRow = $startRowThisChunk + 10; // Row label di bawah foto
                    } else {
                        $labelRow = $startRowThisChunk;
                    }

                    // 2. Label (IPTR / PGAS / Nama Interface)
                    $sheet->setCellValue($colLabel . $labelRow, $item->label ?? '-');
                    $sheet->getStyle($colLabel . $labelRow)->getFont()->setBold(true)->setSize(12);

                    // Fungsi pembantu untuk mencegah "Mbps Mbps"
                    $formatMbps = function($val) {
                        if (!$val) return '0 Mbps';
                        return str_contains(strtolower($val), 'mbps') ? $val : $val . ' Mbps';
                    };

                    // 3. Data Inbound/Outbound
                    $sheet->setCellValue($colLabel . ($labelRow + 1), 'INBOUND');
                    $sheet->setCellValue($colValue . ($labelRow + 1), $formatMbps($item->inbound_value));
                    
                    $sheet->setCellValue($colLabel . ($labelRow + 2), 'OUTBOUND');
                    $sheet->setCellValue($colValue . ($labelRow + 2), $formatMbps($item->outbound_value));

                    if (($labelRow + 4) > $maxRowReached) {
                        $maxRowReached = $labelRow + 4;
                    }
                }
                $row = $maxRowReached + 1; // Jarak antar baris foto
            }

            // --- TABEL SUMMARY (KOMPAK KE SAMPING) ---
            if ($section->summaries->count() > 0) {
                $row++;
                
                // Header Summary
                $currentColNum = ord('A');
                foreach ($section->summaries as $summary) {
                    $c1 = chr($currentColNum);
                    $c2 = chr($currentColNum + 1);
                    
                    $sheet->setCellValue($c1 . $row, $summary->kategori);
                    $sheet->mergeCells($c1 . $row . ':' . $c2 . $row);
                    
                    $style = $sheet->getStyle($c1 . $row . ':' . $c2 . $row);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F4A460');
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    
                    $currentColNum += 2;
                }
                
                $row++;
                
                // Baris INBOUND
                $currentColNum = ord('A');
                foreach ($section->summaries as $summary) {
                    $c1 = chr($currentColNum);
                    $c2 = chr($currentColNum + 1);
                    $sheet->setCellValue($c1 . $row, 'INBOUND');
                    $sheet->setCellValue($c2 . $row, $summary->inbound_value);
                    $sheet->getStyle($c1 . $row . ':' . $c2 . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $currentColNum += 2;
                }
                
                $row++;

                // Baris OUTBOUND
                $currentColNum = ord('A');
                foreach ($section->summaries as $summary) {
                    $c1 = chr($currentColNum);
                    $c2 = chr($currentColNum + 1);
                    $sheet->setCellValue($c1 . $row, 'OUTBOUND');
                    $sheet->setCellValue($c2 . $row, $summary->outbound_value);
                    $sheet->getStyle($c1 . $row . ':' . $c2 . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $currentColNum += 2;
                }
            }

            $row += 3; // Jeda antar section
        }

        // Export as Excel
        $filename = 'Utilization_Report_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

