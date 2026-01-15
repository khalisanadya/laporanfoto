<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Bap;
use App\Models\ReportItem;
use App\Models\ReportPhoto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $myReportIds = $request->input('my_report_ids', []);
        $myBapIds = $request->cookie('my_baps');
        $myBapIds = $myBapIds ? explode(',', $myBapIds) : [];
        $myUtilizationIds = $request->cookie('my_utilization_reports');
        $myUtilizationIds = $myUtilizationIds ? explode(',', $myUtilizationIds) : [];

        // Count reports
        $reportCount = !empty($myReportIds) ? Report::whereIn('id', $myReportIds)->count() : 0;
        $bapCount = !empty($myBapIds) ? Bap::whereIn('id', $myBapIds)->count() : 0;
        $utilizationCount = !empty($myUtilizationIds) ? \App\Models\UtilizationReport::whereIn('id', $myUtilizationIds)->count() : 0;
        $totalReports = $reportCount + $bapCount + $utilizationCount;
        
        $kondisiBaik = 0;
        $kondisiProblem = 0;
        $bulanIni = 0;
        if (!empty($myReportIds)) {
            $kondisiBaik = ReportItem::whereIn('report_id', $myReportIds)
                ->where('kondisi', 'baik')->count();
            $kondisiProblem = ReportItem::whereIn('report_id', $myReportIds)
                ->where('kondisi', 'problem')->count();
            $bulanIni += Report::whereIn('id', $myReportIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        if (!empty($myBapIds)) {
            $bulanIni += Bap::whereIn('id', $myBapIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        if (!empty($myUtilizationIds)) {
            $bulanIni += \App\Models\UtilizationReport::whereIn('id', $myUtilizationIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        
        // Get filter parameters
        $search = $request->input('search');
        $jenisLaporan = $request->input('jenis_laporan');
        $bulanFilter = $request->input('bulan');
        
        // Combine Reports, BAPs, and Utilization Reports for all items with filters
        $allItems = collect();

        if (!empty($myReportIds)) {
            $reportsQuery = Report::whereIn('id', $myReportIds);
            // ...existing code...
            if ($search) {
                $reportsQuery->where(function($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', '%' . $search . '%')
                      ->orWhere('jenis_kegiatan', 'like', '%' . $search . '%')
                      ->orWhere('lokasi_kegiatan', 'like', '%' . $search . '%');
                });
            }
            if ($bulanFilter) {
                $reportsQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanFilter]);
            }
            if ($jenisLaporan === 'bap') {
                // Skip reports
            } else {
                $reports = $reportsQuery->get()->map(function($report) {
                    return (object)[
                        'id' => $report->id,
                        'type' => 'report',
                        'nama' => $report->nama_kegiatan ?? '-',
                        'jenis_laporan' => 'Report Kegiatan',
                        'jenis_kegiatan' => $report->jenis_kegiatan ?? '-',
                        'detail' => $report->lokasi_kegiatan ?? '-',
                        'created_at' => $report->created_at,
                    ];
                });
                $allItems = $allItems->merge($reports);
            }
        }

        if (!empty($myBapIds)) {
            $bapsQuery = Bap::whereIn('id', $myBapIds);
            if ($search) {
                $bapsQuery->where(function($q) use ($search) {
                    $q->where('nomor_bap', 'like', '%' . $search . '%')
                      ->orWhere('nomor_surat_permohonan', 'like', '%' . $search . '%');
                });
            }
            if ($bulanFilter) {
                $bapsQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanFilter]);
            }
            if ($jenisLaporan === 'report') {
                // Skip BAP
            } else {
                $baps = $bapsQuery->get()->map(function($bap) {
                    return (object)[
                        'id' => $bap->id,
                        'type' => 'bap',
                        'nama' => $bap->nomor_bap,
                        'jenis_laporan' => 'BAP',
                        'jenis_kegiatan' => 'Berita Acara Pemeriksaan',
                        'detail' => $bap->tanggal_bap->format('d M Y'),
                        'created_at' => $bap->created_at,
                    ];
                });
                $allItems = $allItems->merge($baps);
            }
        }

        if (!empty($myUtilizationIds)) {
            $utilQuery = \App\Models\UtilizationReport::whereIn('id', $myUtilizationIds);
            if ($search) {
                $utilQuery->where('judul', 'like', '%' . $search . '%');
            }
            if ($bulanFilter) {
                $utilQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanFilter]);
            }
            if ($jenisLaporan === 'report' || $jenisLaporan === 'bap') {
                // Skip utilization jika filter report/bap saja
            } else {
                $utils = $utilQuery->get()->map(function($util) {
                    return (object)[
                        'id' => $util->id,
                        'type' => 'utilization',
                        'nama' => $util->judul ?? '-',
                        'jenis_laporan' => 'Utilization Report',
                        'jenis_kegiatan' => '-',
                        'detail' => $util->periode_mulai->format('d M Y') . ' - ' . $util->periode_selesai->format('d M Y'),
                        'created_at' => $util->created_at,
                    ];
                });
                $allItems = $allItems->merge($utils);
            }
        }
        
        // Sort by created_at desc
        $allItems = $allItems->sortByDesc('created_at')->values();
        
        // Manual pagination
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $total = $allItems->count();
        $items = $allItems->forPage($currentPage, $perPage);
        
        $allItemsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Get available months for filter dropdown
        $availableMonths = collect();
        if (!empty($myReportIds)) {
            $reportMonths = Report::whereIn('id', $myReportIds)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
                ->distinct()
                ->pluck('month');
            $availableMonths = $availableMonths->merge($reportMonths);
        }
        if (!empty($myBapIds)) {
            $bapMonths = Bap::whereIn('id', $myBapIds)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
                ->distinct()
                ->pluck('month');
            $availableMonths = $availableMonths->merge($bapMonths);
        }
        $availableMonths = $availableMonths->unique()->sort()->reverse()->values();
        
        return view('dashboard', compact(
            'totalReports', 
            'kondisiBaik', 
            'kondisiProblem', 
            'bulanIni', 
            'allItemsPaginated',
            'availableMonths',
            'search',
            'jenisLaporan',
            'bulanFilter'
        ));
    }

    public function riwayat(Request $request)
    {
        $myReportIds = $request->input('my_report_ids', []);
        
        if (empty($myReportIds)) {
            $reports = Report::whereRaw('1 = 0')->paginate(10); // Empty result
        } else {
            $query = Report::whereIn('id', $myReportIds)->latest();
            
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                      ->orWhere('jenis_kegiatan', 'like', '%' . $request->search . '%')
                      ->orWhere('lokasi_kegiatan', 'like', '%' . $request->search . '%');
                });
            }
            
            $reports = $query->paginate(10)->withQueryString();
        }
        
        return view('reports.riwayat', compact('reports'));
    }

    public function create()
{
    
    $defaultItems = [
        'Maintenance Perangkat Access Point GS8 (Jakarta)',
        'Maintenance Perangkat Access Point Kebonwaru (Bandung)',
        'Perbaikan Jalur Access Point',
        'Report',
    ];

    return view('reports.report-kegiatan', compact('defaultItems'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
           
            'nama_kegiatan'   => ['nullable', 'string', 'max:255'],
            'waktu_kegiatan'  => ['nullable', 'string', 'max:255'],
            'jenis_kegiatan'  => ['nullable', 'string', 'max:255'],
            'lokasi_kegiatan' => ['nullable', 'string', 'max:255'],

         
            'items' => ['nullable', 'array'],
            'items.*.deskripsi' => ['nullable', 'string', 'max:255'],
            'items.*.kondisi'   => ['nullable', 'in:baik,problem'],
            'items.*.catatan'   => ['nullable', 'string', 'max:255'],

           
            'item_photos' => ['nullable', 'array'],
            'item_photos.*' => ['nullable', 'array'],
            'item_photos.*.*' => ['image', 'max:5120'],

           
            'photo_sections' => ['nullable', 'array'],
            'photo_captions' => ['nullable', 'array'],
        ]);

        $title = $validated['nama_kegiatan']
            ?? $validated['jenis_kegiatan']
            ?? 'Report';

    
        $report = Report::create([
            'nama_kegiatan'   => $validated['nama_kegiatan'] ?? null,
            'waktu_kegiatan'  => $validated['waktu_kegiatan'] ?? null,
            'jenis_kegiatan'  => $validated['jenis_kegiatan'] ?? null,
            'lokasi_kegiatan' => $validated['lokasi_kegiatan'] ?? null,
            'title'           => $title,
        ]);

        $items = collect($validated['items'] ?? [])
            ->filter(function ($row) {
                $des = trim($row['deskripsi'] ?? '');
                $kon = trim($row['kondisi'] ?? '');
                $cat = trim($row['catatan'] ?? '');
                return $des !== '' || $kon !== '' || $cat !== '';
            })
            ->values();

        foreach ($items as $i => $row) {
            ReportItem::create([
                'report_id' => $report->id,
                'no'        => $i + 1,
                'deskripsi' => $row['deskripsi'] ?? null,
                'kondisi'   => $row['kondisi'] ?? null,
                'catatan'   => $row['catatan'] ?? null,
            ]);
        }

        $filesByItem = $request->file('item_photos', []);

        foreach ($filesByItem as $itemIdx => $files) {
            if (!is_array($files)) continue;

            foreach ($files as $photoIdx => $file) {
                if (!$file) continue;

                $path = $file->store("reports/{$report->id}", 'public');

                ReportPhoto::create([
                    'report_id'  => $report->id,
                  
                    'section'    => $request->input("photo_sections.$itemIdx.$photoIdx", 'A'),
                    'caption'    => $request->input("photo_captions.$itemIdx.$photoIdx"),
                    'photo_path' => $path,
                ]);
            }
        }

        // Add report ID to my_reports cookie
        $myReportIds = $request->input('my_report_ids', []);
        $myReportIds[] = $report->id;
        $cookieValue = implode(',', $myReportIds);
        
        return redirect()->route('reports.show', $report)
            ->cookie('my_reports', $cookieValue, 525600, '/', null, false, false);
    }

    public function show(Report $report)
    {
        $report->load(['items', 'photos']);
        return view('reports.show', compact('report'));
    }

    public function pdf(Report $report)
    {
        $report->load(['items', 'photos']);
        $downloadedAt = now()->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';

        $pdf = Pdf::loadView('reports.pdf-report-kegiatan', [
            'report' => $report,
            'downloadedAt' => $downloadedAt,
            'forPdf' => true,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("REPORT-{$report->id}.pdf");
    }

    public function word(Report $report)
    {
        $report->load(['items', 'photos']);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        // Define styles
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 18, 'color' => '0369a1'], ['alignment' => 'center', 'spaceAfter' => 240]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 13, 'color' => '1e293b'], ['spaceBefore' => 240, 'spaceAfter' => 120]);

        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'marginLeft' => 1200,
            'marginRight' => 1200,
        ]);

        // Header with blue background
        $headerTable = $section->addTable(['borderSize' => 0]);
        $headerTable->addRow(800);
        $headerCell = $headerTable->addCell(9000, ['bgColor' => '0369a1', 'valign' => 'center']);
        $headerCell->addText('LAPORAN KEGIATAN', ['bold' => true, 'size' => 20, 'color' => 'FFFFFF'], ['alignment' => 'center', 'spaceBefore' => 150, 'spaceAfter' => 150]);

        $section->addTextBreak(1);

        // Info Kegiatan Section
        $section->addText('INFORMASI KEGIATAN', ['bold' => true, 'size' => 13, 'color' => '0369a1'], ['spaceBefore' => 200, 'spaceAfter' => 150]);

        $infoTableStyle = [
            'borderSize' => 6,
            'borderColor' => 'E2E8F0',
            'cellMargin' => 80,
        ];
        $infoTable = $section->addTable($infoTableStyle);
        
        $labelStyle = ['bold' => true, 'size' => 11, 'color' => '475569'];
        $valueStyle = ['size' => 11, 'color' => '1e293b'];
        $cellLabel = ['bgColor' => 'F8FAFC', 'valign' => 'center'];
        $cellValue = ['valign' => 'center'];

        $infoData = [
            ['Nama Kegiatan', $report->nama_kegiatan ?? '-'],
            ['Waktu Kegiatan', $report->waktu_kegiatan ?? '-'],
            ['Jenis Kegiatan', $report->jenis_kegiatan ?? '-'],
            ['Lokasi Kegiatan', $report->lokasi_kegiatan ?? '-'],
        ];

        foreach ($infoData as $row) {
            $infoTable->addRow(400);
            $infoTable->addCell(2800, $cellLabel)->addText($row[0], $labelStyle, ['spaceAfter' => 0]);
            $infoTable->addCell(6200, $cellValue)->addText($row[1], $valueStyle, ['spaceAfter' => 0]);
        }

        $section->addTextBreak(1);

        // Checklist Kondisi Section
        $section->addText('CHECKLIST KONDISI', ['bold' => true, 'size' => 13, 'color' => '0369a1'], ['spaceBefore' => 200, 'spaceAfter' => 150]);

        $checkTableStyle = [
            'borderSize' => 6,
            'borderColor' => 'E2E8F0',
            'cellMargin' => 80,
        ];
        $checkTable = $section->addTable($checkTableStyle);
        
        // Header row
        $headerCellStyle = ['bgColor' => '0369a1', 'valign' => 'center'];
        $headerTextStyle = ['bold' => true, 'size' => 10, 'color' => 'FFFFFF'];
        
        $checkTable->addRow(400);
        $checkTable->addCell(600, $headerCellStyle)->addText('No', $headerTextStyle, ['alignment' => 'center', 'spaceAfter' => 0]);
        $checkTable->addCell(3200, $headerCellStyle)->addText('Deskripsi', $headerTextStyle, ['spaceAfter' => 0]);
        $checkTable->addCell(900, $headerCellStyle)->addText('Baik', $headerTextStyle, ['alignment' => 'center', 'spaceAfter' => 0]);
        $checkTable->addCell(900, $headerCellStyle)->addText('Problem', $headerTextStyle, ['alignment' => 'center', 'spaceAfter' => 0]);
        $checkTable->addCell(3400, $headerCellStyle)->addText('Catatan', $headerTextStyle, ['spaceAfter' => 0]);

        // Data rows
        $rowNum = 0;
        foreach ($report->items as $item) {
            $rowNum++;
            $rowBg = $rowNum % 2 == 0 ? 'F8FAFC' : 'FFFFFF';
            $rowCellStyle = ['bgColor' => $rowBg, 'valign' => 'center'];
            
            $baikMark = $item->kondisi === 'baik' ? '✓' : '';
            $problemMark = $item->kondisi === 'problem' ? '✓' : '';
            
            $checkTable->addRow(350);
            $checkTable->addCell(600, $rowCellStyle)->addText($item->no, ['size' => 10], ['alignment' => 'center', 'spaceAfter' => 0]);
            $checkTable->addCell(3200, $rowCellStyle)->addText($item->deskripsi ?? '-', ['size' => 10], ['spaceAfter' => 0]);
            $checkTable->addCell(900, $rowCellStyle)->addText($baikMark, ['size' => 12, 'bold' => true, 'color' => '10b981'], ['alignment' => 'center', 'spaceAfter' => 0]);
            $checkTable->addCell(900, $rowCellStyle)->addText($problemMark, ['size' => 12, 'bold' => true, 'color' => 'ef4444'], ['alignment' => 'center', 'spaceAfter' => 0]);
            $checkTable->addCell(3400, $rowCellStyle)->addText($item->catatan ?? '-', ['size' => 10, 'color' => '64748b'], ['spaceAfter' => 0]);
        }

        $section->addTextBreak(1);

        // Dokumentasi Foto Section
        if ($report->photos->count() > 0) {
            $section->addText('DOKUMENTASI FOTO', ['bold' => true, 'size' => 13, 'color' => '0369a1'], ['spaceBefore' => 200, 'spaceAfter' => 150]);

            $photoTableStyle = [
                'borderSize' => 6,
                'borderColor' => 'E2E8F0',
                'cellMargin' => 100,
            ];
            
            // 2 photos per row
            $photos = $report->photos->values();
            $photoCount = $photos->count();
            
            for ($i = 0; $i < $photoCount; $i += 2) {
                $photoTable = $section->addTable($photoTableStyle);
                $photoTable->addRow();
                
                // First photo
                $photo1 = $photos[$i];
                $photoPath1 = public_path('storage/' . $photo1->photo_path);
                $cell1 = $photoTable->addCell(4500, ['valign' => 'top', 'bgColor' => 'FFFFFF']);
                
                if (file_exists($photoPath1)) {
                    // Get image dimensions to maintain aspect ratio
                    $imgSize1 = @getimagesize($photoPath1);
                    $imgWidth1 = 140;
                    if ($imgSize1 && $imgSize1[0] > 0) {
                        $ratio1 = $imgSize1[1] / $imgSize1[0];
                        $imgHeight1 = $imgWidth1 * $ratio1;
                    } else {
                        $imgHeight1 = 105;
                    }
                    $cell1->addImage($photoPath1, [
                        'width' => $imgWidth1,
                        'height' => $imgHeight1,
                        'alignment' => 'center',
                        'wrappingStyle' => 'inline',
                    ]);
                }
                $cell1->addText($photo1->caption ?? 'Dokumentasi', ['size' => 9, 'italic' => true, 'color' => '64748b'], ['alignment' => 'center', 'spaceBefore' => 100]);
                
                // Second photo (if exists)
                if (isset($photos[$i + 1])) {
                    $photo2 = $photos[$i + 1];
                    $photoPath2 = public_path('storage/' . $photo2->photo_path);
                    $cell2 = $photoTable->addCell(4500, ['valign' => 'top', 'bgColor' => 'FFFFFF']);
                    
                    if (file_exists($photoPath2)) {
                        // Get image dimensions to maintain aspect ratio
                        $imgSize2 = @getimagesize($photoPath2);
                        $imgWidth2 = 140;
                        if ($imgSize2 && $imgSize2[0] > 0) {
                            $ratio2 = $imgSize2[1] / $imgSize2[0];
                            $imgHeight2 = $imgWidth2 * $ratio2;
                        } else {
                            $imgHeight2 = 105;
                        }
                        $cell2->addImage($photoPath2, [
                            'width' => $imgWidth2,
                            'height' => $imgHeight2,
                            'alignment' => 'center',
                            'wrappingStyle' => 'inline',
                        ]);
                    }
                    $cell2->addText($photo2->caption ?? 'Dokumentasi', ['size' => 9, 'italic' => true, 'color' => '64748b'], ['alignment' => 'center', 'spaceBefore' => 100]);
                }
                
                $section->addTextBreak(1);
            }
        }

        $section->addTextBreak(1);

        // Footer
        $downloadedAt = now()->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';
        $section->addText(
            'Dokumen ini diunduh pada ' . $downloadedAt,
            ['size' => 9, 'color' => '94a3b8', 'italic' => true],
            ['alignment' => 'center']
        );

        // Save file
        $filename = "REPORT-{$report->id}.docx";
        $temp = storage_path("app/{$filename}");
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }
}
