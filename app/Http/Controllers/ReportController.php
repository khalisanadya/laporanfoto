<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportItem;
use App\Models\ReportPhoto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $deviceId = $request->input('device_id');
        
        $totalReports = Report::where('device_id', $deviceId)->count();
        
        $kondisiBaik = ReportItem::whereHas('report', function($q) use ($deviceId) {
            $q->where('device_id', $deviceId);
        })->where('kondisi', 'baik')->count();
        
        $kondisiProblem = ReportItem::whereHas('report', function($q) use ($deviceId) {
            $q->where('device_id', $deviceId);
        })->where('kondisi', 'problem')->count();
        
        $bulanIni = Report::where('device_id', $deviceId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $recentReports = Report::where('device_id', $deviceId)->latest()->take(5)->get();
        
        return view('dashboard', compact('totalReports', 'kondisiBaik', 'kondisiProblem', 'bulanIni', 'recentReports'));
    }

    public function riwayat(Request $request)
    {
        $deviceId = $request->input('device_id');
        $query = Report::where('device_id', $deviceId)->latest();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('jenis_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi_kegiatan', 'like', '%' . $request->search . '%');
            });
        }
        
        $reports = $query->paginate(10)->withQueryString();
        
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
            'device_id'       => $request->input('device_id'),
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

        return redirect()->route('reports.pdf', $report);
    }

    public function show(Report $report)
    {
        $report->load(['items', 'photos']);
        return view('reports.pdf-report-kegiatan', compact('report'));
    }

    public function pdf(Report $report)
    {
        $report->load(['items', 'photos']);
        $downloadedAt = now()->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';

        $pdf = Pdf::loadView('reports.pdf-report-kegiatan', [
            'report' => $report,
            'downloadedAt' => $downloadedAt,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("REPORT-{$report->id}.pdf");
    }
}
