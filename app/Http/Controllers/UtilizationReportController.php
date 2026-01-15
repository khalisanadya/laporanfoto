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

        // Process sections
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionIndex => $sectionData) {
                $section = UtilizationSection::create([
                    'utilization_report_id' => $report->id,
                    'nama_section' => $sectionData['nama'] ?? 'Section ' . ($sectionIndex + 1),
                    'warna_header' => $sectionData['warna'] ?? '#FFA500',
                    'urutan' => $sectionIndex,
                ]);

                // Process items in section
                if (isset($sectionData['items'])) {
                    foreach ($sectionData['items'] as $itemIndex => $itemData) {
                        $gambarPath = null;
                        
                        // Handle image upload (support DataTransfer from JS)
                        $gambarInput = "sections.{$sectionIndex}.items.{$itemIndex}.gambar";
                        if ($request->hasFile($gambarInput)) {
                            $gambar = $request->file($gambarInput);
                            if (is_array($gambar)) {
                                // If somehow multiple files, ambil yang pertama
                                $gambar = $gambar[0];
                            }
                            $gambarPath = $gambar->store('utilization-graphs', 'public');
                        }

                        UtilizationItem::create([
                            'utilization_section_id' => $section->id,
                            'nama_interface' => $itemData['nama_interface'] ?? null,
                            // label caption dari form
                            'label' => $itemData['label_caption'] ?? ($itemData['label'] ?? null),
                            'inbound_current' => $itemData['inbound_current'] ?? null,
                            'inbound_average' => $itemData['inbound_average'] ?? null,
                            'inbound_maximum' => $itemData['inbound_maximum'] ?? null,
                            'outbound_current' => $itemData['outbound_current'] ?? null,
                            'outbound_average' => $itemData['outbound_average'] ?? null,
                            'outbound_maximum' => $itemData['outbound_maximum'] ?? null,
                            // label inbound/outbound dari form
                            'inbound_value' => $itemData['label_inbound'] ?? ($itemData['inbound_value'] ?? null),
                            'outbound_value' => $itemData['label_outbound'] ?? ($itemData['outbound_value'] ?? null),
                            'gambar_graph' => $gambarPath,
                            'urutan' => $itemIndex,
                        ]);
                    }
                }

                // Process summaries
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

        // Cookie tracking
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

        // Set default column widths
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        $row = 1;

        // Title
        $sheet->setCellValue('D' . $row, $utilization->judul);
        $sheet->mergeCells('D' . $row . ':F' . $row);
        $sheet->getStyle('D' . $row . ':F' . $row)->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('D' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Period
        $periodeText = 'Periode: ' . $utilization->periode_mulai->translatedFormat('d F Y') . ' - ' . $utilization->periode_selesai->translatedFormat('d F Y');
        $sheet->setCellValue('A' . $row, $periodeText);
        $sheet->mergeCells('A' . $row . ':H' . $row);
        $sheet->getStyle('A' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $row += 2;

        foreach ($utilization->sections as $section) {
            // Section header (warna hanya sepanjang teks)
            $sheet->setCellValue('A' . $row, $section->nama_section);
            $sheet->getStyle('A' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB(str_replace('#', '', $section->warna_header));
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            // Items
            foreach ($section->items as $item) {
                // Add graph image first, bigger size
                if ($item->gambar_graph && Storage::disk('public')->exists($item->gambar_graph)) {
                    $imagePath = Storage::disk('public')->path($item->gambar_graph);
                    $drawing = new Drawing();
                    $drawing->setName('Graph');
                    $drawing->setDescription('Traffic Graph');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(220); // Perbesar gambar
                    $drawing->setCoordinates('A' . $row);
                    $drawing->setWorksheet($sheet);
                    $row += 14; // Space for bigger image
                }
                // Label, inbound, outbound di bawah gambar
                if ($item->label || $item->inbound_value || $item->outbound_value) {
                    $sheet->setCellValue('A' . $row, $item->label ?? '-');
                    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                    $row++;
                    $sheet->setCellValue('A' . $row, 'INBOUND');
                    $sheet->setCellValue('B' . $row, $item->inbound_value ?? '-');
                    $row++;
                    $sheet->setCellValue('A' . $row, 'OUTBOUND');
                    $sheet->setCellValue('B' . $row, $item->outbound_value ?? '-');
                    $row++;
                }
            }

            // Summary table
            if ($section->summaries->count() > 0) {
                $row++;
                $colIndex = 0;
                $colCount = count($section->summaries);
                // Header row (kategori, merge 2 kolom per kategori)
                foreach ($section->summaries as $summary) {
                    $col = chr(ord('A') + $colIndex * 2);
                    $col2 = chr(ord('A') + $colIndex * 2 + 1);
                    $sheet->setCellValue($col . $row, $summary->kategori);
                    $sheet->mergeCells($col . $row . ':' . $col2 . $row);
                    $sheet->getStyle($col . $row . ':' . $col2 . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F4A460');
                    $sheet->getStyle($col . $row . ':' . $col2 . $row)->getFont()->setBold(true);
                    $sheet->getStyle($col . $row . ':' . $col2 . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $colIndex++;
                }
                // Border header
                $sheet->getStyle('A' . $row . ':' . chr(ord('A') + $colCount * 2 - 1) . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
                // INBOUND row: INBOUND, angka, INBOUND, angka, ...
                $colIndex = 0;
                foreach ($section->summaries as $summary) {
                    $col = chr(ord('A') + $colIndex * 2);
                    $col2 = chr(ord('A') + $colIndex * 2 + 1);
                    $sheet->setCellValue($col . $row, 'INBOUND');
                    $sheet->setCellValue($col2 . $row, $summary->inbound_value);
                    $sheet->getStyle($col . $row . ':' . $col2 . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $colIndex++;
                }
                $sheet->getStyle('A' . $row . ':' . chr(ord('A') + $colCount * 2 - 1) . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
                // OUTBOUND row: OUTBOUND, angka, OUTBOUND, angka, ...
                $colIndex = 0;
                foreach ($section->summaries as $summary) {
                    $col = chr(ord('A') + $colIndex * 2);
                    $col2 = chr(ord('A') + $colIndex * 2 + 1);
                    $sheet->setCellValue($col . $row, 'OUTBOUND');
                    $sheet->setCellValue($col2 . $row, $summary->outbound_value);
                    $sheet->getStyle($col . $row . ':' . $col2 . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $colIndex++;
                }
                $sheet->getStyle('A' . $row . ':' . chr(ord('A') + $colCount * 2 - 1) . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            $row += 2; // Space between sections
        }

        // Output
        $filename = 'Utilization_Report_' . $utilization->id . '_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
