<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportItem;
use App\Models\ReportPhoto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
  public function create()
  {
    
    $defaultItems = [
      'Maintenance Perangkat Access Point GS8 (Jakarta)',
      'Maintenance Perangkat Access Point Kebonwaru (Bandung)',
      'Perbaikan Jalur Access Point',
      'Report',
    ];
    return view('reports.index', compact('defaultItems'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nama_kegiatan' => ['required','string','max:255'],
      'waktu_kegiatan' => ['required','string','max:255'],
      'jenis_kegiatan' => ['required','string','max:255'],
      'lokasi_kegiatan' => ['required','string','max:255'],

      'items' => ['required','array','min:1'],
      'items.*.deskripsi' => ['required','string','max:255'],
      'items.*.kondisi' => ['nullable','in:baik,problem'],
      'items.*.catatan' => ['nullable','string','max:255'],

      'photos' => ['nullable','array'],
      'photos.*' => ['image','max:5120'],
      'photo_sections' => ['nullable','array'],
      'photo_captions' => ['nullable','array'],
    ]);

    $report = Report::create([
      'nama_kegiatan' => $validated['nama_kegiatan'],
      'waktu_kegiatan' => $validated['waktu_kegiatan'],
      'jenis_kegiatan' => $validated['jenis_kegiatan'],
      'lokasi_kegiatan' => $validated['lokasi_kegiatan'],
    ]);

    foreach ($validated['items'] as $i => $row) {
      ReportItem::create([
        'report_id' => $report->id,
        'no' => $i + 1,
        'deskripsi' => $row['deskripsi'],
        'kondisi' => $row['kondisi'] ?? null,
        'catatan' => $row['catatan'] ?? null,
      ]);
    }

    $files = $request->file('photos', []);
    foreach ($files as $idx => $file) {
      $path = $file->store("reports/{$report->id}", 'public');
      ReportPhoto::create([
        'report_id' => $report->id,
        'section' => $request->input("photo_sections.$idx", 'A'),
        'caption' => $request->input("photo_captions.$idx"),
        'photo_path' => $path,
      ]);
    }

    return redirect()->route('reports.show', $report);
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

    $pdf = Pdf::loadView('reports.pdf', [
      'report' => $report,
      'downloadedAt' => $downloadedAt,
    ])->setPaper('a4', 'portrait');

    return $pdf->download("REPORT-{$report->id}.pdf");
  }
}

