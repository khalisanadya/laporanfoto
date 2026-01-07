<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Report Kegiatan</title>

  <style>
    @page { margin: 30px 30px; }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #111827;
    }

    /* Header */
    .title-wrap { text-align: center; margin-bottom: 25px; }
    .title { font-size: 18px; font-weight: 800; letter-spacing: .4px; }
    .muted { color: #6b7280; font-size: 10px; }

    /* Tables */
    table { width: 100%; border-collapse: collapse; }

    .info td {
      border: 1px solid #111827;
      padding: 7px 9px;
      vertical-align: top;
    }
    .info td:first-child {
      width: 32%;
      font-weight: 800;
      background: #f3f4f6;
    }

    .section { margin-top: 35px; } 
    .section-title {
      font-weight: 900;
      font-size: 16px;
      margin-bottom: 12px;
      color: #111827;
    }

    .check th, .check td { border: 1px solid #111827; padding: 8px 8px; }
    .check th {
      background: #f3f4f6;
      font-weight: 900;
      text-align: center;
    }
    .check td { vertical-align: middle; }

    .center { text-align: center; }
    .no { width: 40px; text-align: center; font-weight: 800; }
    .desc { width: 52%; }
    .cond { width: 90px; text-align: center; font-size: 14px; } 
    .note { width: 26%; }

    .footer { margin-top: 25px; font-size: 10px; color: #6b7280; text-align: right; }

    /* FOTO */
    .photo-grid { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .photo-cell {
      border: 1px solid #111827;
      padding: 15px;
      vertical-align: top;
      background: #ffffff;
    }

    .photo-wrap{
      height: 240px;
      display: table;
      width: 100%;
    }
    .photo-wrap-inner{
      display: table-cell;
      vertical-align: middle;
      text-align: center;
    }

    .photo-img{
      max-width: 100%;
      max-height: 220px;
      width: auto;
      height: auto;
      display: inline-block;
    }

    .cap {
      margin-top: 1px;
      font-size: 14px;
      font-weight: 300;
      text-align: center;
      color: #111827;
    }
  </style>
</head>

<body>

  {{-- HEADER --}}
  <div class="title-wrap">
    <div class="title">REPORT KEGIATAN</div>
  </div>

  {{-- INFO --}}
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

  {{-- CHECKLIST --}}
  <div class="section">
    <div class="section-title">Checklist Kondisi</div>

    <table class="check">
      <thead>
        <tr>
          <th rowspan="2" class="no">No</th>
          <th rowspan="2">Deskripsi</th>
          <th colspan="2">Kondisi</th>
          <th rowspan="2">Catatan</th>
        </tr>
        <tr>
          <th class="cond">Baik</th>
          <th class="cond">Problem</th>
        </tr>
      </thead>

      <tbody>
        @forelse($report->items as $item)
          <tr>
            <td class="no">{{ $item->no }}</td>
            <td class="desc">{{ $item->deskripsi ?? '-' }}</td>

            
            <td class="cond center">
                {!! (strtolower($item->kondisi) === 'baik') ? '&#10003;' : '' !!}
            </td>
            <td class="cond center">
                {!! (strtolower($item->kondisi) === 'problem') ? '&#10003;' : '' !!}
            </td>

            <td class="note">{{ $item->catatan ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="center">Tidak ada checklist.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- DOKUMENTASI --}}
  @if($report->photos && $report->photos->count())
  
    <div class="section">
      <div class="section-title">Dokumentasi</div>

      <table class="photo-grid">
        @foreach($report->photos->chunk(2) as $row)
          <tr>
            @foreach($row as $photo)
              <td class="photo-cell" style="width:50%;">
                <div class="photo-wrap">
                  <div class="photo-wrap-inner">
                    <img
                      class="photo-img"
                      src="{{ public_path('storage/'.$photo->photo_path) }}"
                      alt="foto"
                    >
                  </div>
                </div>

                <div class="cap">
                  {{ $photo->caption ?? 'Dokumentasi' }}
                </div>
              </td>
            @endforeach

            @if($row->count() === 1)
              <td class="photo-cell" style="width:50%; border:0;"></td>
            @endif
          </tr>
        @endforeach
      </table>
    </div>
  @endif

  <div class="footer">
    Generated: {{ $downloadedAt ?? now()->timezone('Asia/Jakarta')->format('d M Y H:i').' WIB' }}
  </div>

</body>
</html>