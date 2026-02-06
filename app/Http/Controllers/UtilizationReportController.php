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

        // --- PENGATURAN LEBAR KOLOM SERAGAM (UNTUK SUMMARY & JARAK FOTO) ---
        for ($i = 1; $i <= 15; $i++) {
            $colLetter = $this->getColLetter($i);
            if ($i % 2 != 0) {
                $sheet->getColumnDimension($colLetter)->setWidth(28); // Kolom Label (A, C, E...)
            } else {
                $sheet->getColumnDimension($colLetter)->setWidth(18); // Kolom Nilai (B, D, F...)
            }
        }

        $row = 1;

        // JUDUL LAPORAN
        $sheet->setCellValue('A' . $row, strtoupper($utilization->judul));
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // PERIODE (DITAMBAHKAN KEMBALI)
        $periodeText = 'Periode: ' . $utilization->periode_mulai->translatedFormat('d F Y') . ' - ' . $utilization->periode_selesai->translatedFormat('d F Y');
        $sheet->setCellValue('A' . $row, $periodeText);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;

        foreach ($utilization->sections as $section) {
            // --- SECTION HIGHLIGHT (BOX KECIL DI KIRI SEPERTI PREVIEW) ---
            $sheet->setCellValue('A' . $row, ' ' . $section->nama_section);
            $sheet->mergeCells('A' . $row . ':B' . $row); 
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB(str_replace('#', '', $section->warna_header));
            $row += 2;

            $items = $section->items->chunk(2);
            foreach ($items as $chunk) {
                $imageRow = $row;
                $sheet->getRowDimension($imageRow)->setRowHeight(140); 

                foreach ($chunk as $index => $item) {
                    $isLeft = ($index % 2 == 0);
                    // Area Foto tetap di A dan D (dengan C sebagai spacer lebar)
                    $col = $isLeft ? 'A' : 'D';

                    if ($item->gambar_graph && Storage::disk('public')->exists($item->gambar_graph)) {
                        $imagePath = Storage::disk('public')->path($item->gambar_graph);
                        $drawing = new Drawing();
                        $drawing->setPath($imagePath);
                        $drawing->setHeight(165); 
                        $drawing->setCoordinates($col . $imageRow);
                        $drawing->setOffsetX(5);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);
                    }
                }

                $row++; 
                foreach ($chunk as $index => $item) {
                    $isLeft = ($index % 2 == 0);
                    $colL = $isLeft ? 'A' : 'D';
                    $colV = $isLeft ? 'B' : 'E';

                    // Nama Label Bold tepat di bawah foto
                    $sheet->setCellValue($colL . $row, $item->label ?? '-');
                    $sheet->getStyle($colL . $row)->getFont()->setBold(true);

                    $formatMbps = function($val) {
                        if (!$val) return '0 Mbps';
                        return str_contains(strtolower($val), 'mbps') ? $val : $val . ' Mbps';
                    };

                    // Data Row Inbound/Outbound
                    $sheet->setCellValue($colL . ($row + 1), 'INBOUND');
                    $sheet->setCellValue($colV . ($row + 1), $formatMbps($item->inbound_value));
                    
                    $sheet->setCellValue($colL . ($row + 2), 'OUTBOUND');
                    $sheet->setCellValue($colV . ($row + 2), $formatMbps($item->outbound_value));

                    $sheet->getStyle($colV . ($row + 1) . ':' . $colV . ($row + 2))
                          ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
                $row += 5; 
            }

            // --- SUMMARY SECTION (DISATUKAN & UKURAN SERAGAM) ---
            if ($section->summaries->count() > 0) {
                $row++;
                $headerRow = $row;
                $startColIdx = 1; 

                foreach ($section->summaries as $summary) {
                    $c1 = $this->getColLetter($startColIdx);
                    $c2 = $this->getColLetter($startColIdx + 1);

                    // Header Summary
                    $sheet->setCellValue($c1 . $headerRow, $summary->kategori);
                    $sheet->mergeCells($c1 . $headerRow . ':' . $c2 . $headerRow);
                    $sheet->getStyle($c1 . $headerRow . ':' . $c2 . $headerRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F4A460']],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);

                    // Data Inbound
                    $sheet->setCellValue($c1 . ($headerRow + 1), '  INBOUND');
                    $sheet->setCellValue($c2 . ($headerRow + 1), $summary->inbound_value);
                    $sheet->getStyle($c1 . ($headerRow + 1) . ':' . $c2 . ($headerRow + 1))
                        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($c2 . ($headerRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Data Outbound
                    $sheet->setCellValue($c1 . ($headerRow + 2), '  OUTBOUND');
                    $sheet->setCellValue($c2 . ($headerRow + 2), $summary->outbound_value);
                    $sheet->getStyle($c1 . ($headerRow + 2) . ':' . $c2 . ($headerRow + 2))
                        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($c2 . ($headerRow + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $startColIdx += 2; // Tabel menyatu tanpa gap
                }
                $row += 4;
            }
            $row += 2;
        }

        $filename = 'Utilization_Report_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function getColLetter($num) {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return $this->getColLetter($num2) . $letter;
        }
        return $letter;
    }
}