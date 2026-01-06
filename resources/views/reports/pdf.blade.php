<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>REPORT KEGIATAN</title>
    <style>
        @page { margin: 28px 28px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }

        .title-wrap { text-align: center; margin-bottom: 14px; }
        .title { font-size: 18px; font-weight: 800; letter-spacing: .4px; }
        .subtitle { font-size: 12px; font-weight: 700; margin-top: 4px; }

        .muted { color: #555; font-size: 10px; }

        table { width: 100%; border-collapse: collapse; }
        .info td { border: 1px solid #333; padding: 6px 8px; vertical-align: top; }
        .info td:first-child { width: 32%; font-weight: 700; background: #f3f4f6; }

        .section { margin-top: 14px; }
        .section-title { font-weight: 800; font-size: 13px; margin-bottom: 6px; }

        .check th, .check td { border: 1px solid #333; padding: 6px 6px; }
        .check th { background: #f3f4f6; font-weight: 800; text-align: center; }
        .check td { vertical-align: top; }
        .center { text-align: center; }
        .no { width: 40px; text-align: center; }
        .desc { width: 52%; }
        .cond { width: 90px; text-align: center; }
        .note { width: 26%; }

        .page-break { page-break-after: always; }

        /* Photo grid using table for dompdf compatibility */
        .photo-grid { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .photo-cell { border: 1px solid #333; padding: 6px; vertical-align: top; }
        .photo-img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            display: block;
            border: 1px solid #ddd;
        }
        .cap { margin-top: 6px; font-size: 10px; font-weight: 700; text-align: center; }

        .footer { margin-top: 10px; font-size: 10px; color: #555; text-align: right; }
    </style>
</head>
<body>

@php
    // Fallback biar gak blank
    $judulAtas = 'REPORT KEGIATAN';
    $judulBawah = strtoupper($report->title ?? 'MANAGED SERVICE PGNMAS');

    $items = $report->items ?? collect();
    $photos = $report->photos ?? collect();

    // group photos by section (A/B/C). kalau null -> "DOKUMENTASI"
    $photoGroups = $photos->groupBy(function($p){
        return $p->section ?: 'DOKUMENTASI';
    });

    // helper chunk photo into pages: 4 photos per page (2x2)
    $chunkPerPage = 4;
@endphp

<div class="title-wrap">
    <div class="title">{{ $judulAtas }}</div>
    <div class="subtitle">{{ $judulBawah }}</div>
</div>

<table class="info">
    <tr>
        <td>Nama Kegiatan</td>
        <td>{{ $report->nama_kegiatan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Waktu Kegiatan</td>
        <td>{{ $report->waktu_kegiatan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Jenis Kegiatan</td>
        <td>{{ $report->jenis_kegiatan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Lokasi Kegiatan</td>
        <td>{{ $report->lokasi_kegiatan ?? '-' }}</td>
    </tr>
</table>

<div class="section">
    <div class="section-title">Checklist Kondisi</div>

    <table class="check">
        <thead>
        <tr>
            <th class="no" rowspan="2">NO</th>
            <th class="desc" rowspan="2">Deskripsi</th>
            <th class="center" colspan="2">Kondisi</th>
            <th class="note" rowspan="2">Catatan</th>
        </tr>
        <tr>
            <th class="cond">Baik</th>
            <th class="cond">Problem</th>
        </tr>
        </thead>
        <tbody>
        @if($items->count() === 0)
            <tr>
                <td class="center" colspan="5" style="padding:10px">- Tidak ada checklist -</td>
            </tr>
        @else
            @foreach($items as $it)
                @php
                    $isBaik = ($it->kondisi === 'baik');
                    $isProb = ($it->kondisi === 'problem');
                @endphp
                <tr>
                    <td class="no">{{ $it->no }}</td>
                    <td class="desc">{{ $it->deskripsi }}</td>
                    <td class="cond">{{ $isBaik ? '✓' : '' }}</td>
                    <td class="cond">{{ $isProb ? '✓' : '' }}</td>
                    <td class="note">{{ $it->catatan ?? '' }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    <div class="footer">
        <div class="muted">Generated: {{ $downloadedAt ?? '' }}</div>
    </div>
</div>

<div class="page-break"></div>

{{-- PHOTO PAGES --}}
@php $sectionIndex = 0; @endphp
@foreach($photoGroups as $sec => $group)
    @php
        $sectionIndex++;
        // kasih label A/B/C kalau section bukan "DOKUMENTASI"
        $sectionLabel = $sec;
        // chunk per page
        $chunks = $group->values()->chunk($chunkPerPage);
    @endphp

    @foreach($chunks as $pageIdx => $chunk)
        <div class="section-title">
            {{ is_string($sec) ? (strlen($sec) <= 2 ? ($sec.'. Dokumentasi') : $sec) : 'Dokumentasi' }}
        </div>

        <table class="photo-grid">
            @php
                // 2 kolom, 2 baris => total 4 slot
                $cells = $chunk->values();
                $totalSlots = 4;
            @endphp

            @for($r=0; $r<2; $r++)
                <tr>
                    @for($c=0; $c<2; $c++)
                        @php
                            $idx = ($r*2)+$c;
                            $p = $cells->get($idx);
                        @endphp
                        <td class="photo-cell">
                            @if($p)
                                <img class="photo-img" src="{{ public_path('storage/'.$p->photo_path) }}" alt="foto">
                                <div class="cap">{{ $p->caption ?? 'Dokumentasi' }}</div>
                            @else
                                <div style="height:190px;"></div>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>

        @if(!($loop->last && $loop->parent->last))
            <div class="page-break"></div>
        @endif
    @endforeach
@endforeach

</body>
</html>
